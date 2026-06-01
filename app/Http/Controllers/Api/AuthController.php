<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConnectionRequest;
use App\Models\Otp;
use App\Models\User;
use App\Models\Prahari;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Register user 
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|max:15|unique:praharis',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $lastPrahari = Prahari::where('prahari_id', 'like', 'PR%')->latest('id')->first();
        if ($lastPrahari && preg_match('/PR(\d+)/', $lastPrahari->prahari_id, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
            $nextPrahariId = 'PR' . $nextNumber;
        } else {
            $nextPrahariId = 'PR1001';
        }

        Prahari::create([
            'user_id' => $user->id,
            'prahari_id' => $nextPrahariId,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'status' => 'active',
            'aadhaar_status' => 'verified',
            'wallet_balance' => 0.00
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => $user
            ]
        ]);
    }

    /**
     *user login.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }

    /**
     * user logout.
     */             
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $conn_id = $request->header('connection_id');

        $data = ConnectionRequest::where('connection_id', $conn_id)->first();
        if($data){
            $data->update([
                'auth_code'=> null
            ]);
        }


        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function getConnectionId(Request  $request){
            $app_api_key = config('app.api_key');
            if($app_api_key == $request->header('app_api_key')){
                $data  = ConnectionRequest::create([
                    'connection_id' => Str::random(32)
                ]);
                return response()->json([
                    'status'=>200,
                    'message' => 'connection id generated successfully',
                    'data' => [
                        'connection_id' => $data->connection_id
                    ]
                ]);
            }
            return response()->json([
                'status'=>401,
                'message' => 'Invalid api key',
                'data'=> $app_api_key,
                'data2'=> $request->header('app_api_key')
            ]);
    }

    public function requestOtp(Request $request){
            $conn = $this->validateConnection($request->header('connection_id'));
            if(!$conn){
                return response()->json([
                'status' => 400,
                'message' => 'Invalid Connection. Please generate Connection Id',
                'data' => null,
            ]);
            }

            $validated = Validator::make($request->all(),[
                'mobile'=>'required|string|min:10|max:15',
            ]);

            if ($validated->fails()){
                return response()->json([
                    'status'=> 400,
                    'message'=> 'Valdation Error',
                    'data'=> $validated->errors(),
                ]);
            }


            $otp = $this->generateOtp($request->mobile);
            $smsService = new SmsService();
            $data = $smsService->sendSmsOtp($otp);
            if($data['type']==='success'){
                return response()->json([
                    'status' => 200,
                    'message' => 'OTP sent successfully',
                    'data' => [
                        'otp_sent' => true
                    ]
                ]);

            }
            return response()->json([
                'status'=> 400,
                'message'=> 'Otp not sent',
                'data'=> null,
            ]);

    }

    public function verifyOtp(Request $request){
           $conn  = $this->validateConnection($request->header('connection_id'));
           if(!$conn){
            return response()->json([
                'status' => 400,
                'message' => 'Invalid Connection. Please generate Connection Id',
                'data' => null,
            ]);
           }

            $validator = Validator::make($request->all(), [
                'name'=>'required|string|max:255',
                'otp' => 'required',
                'mobile'=>'required',
            ]);
            if($validator->fails()){
                return response()->json([
                    'status'=> 400,
                    'message'=> 'Valdation Error',
                    'data'=> $validator->errors(),
                ]);
            }

            $saved_otp = Otp::where('mobile', $request->mobile)->where('otp', $request->otp)->first();
            if (!$saved_otp) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid OTP...',
                    'data' => null,
                ]);
            }

            $expiry_time = 5;
            $expiry_validation = Carbon::parse($saved_otp->updated_at)->addMinutes($expiry_time)->isPast();
            if($expiry_validation){
                return response()->json([
                    'status' => false,
                    'message' => 'OTP Expired...',
                    'data' => null,
                ]);
            }

            if($saved_otp->otp === $request->otp){
                $prahari = Prahari::where('mobile', $request->mobile)->first();

                if (!$prahari) {

                    // Create User
                    $newUser = User::create([
                        'name'     => $request->name,
                        'mobile'   => $request->mobile,
                        'password' => Hash::make($request->otp),
                    ]);

                    // Create Prahari Profile
                    $prahari = Prahari::create([
                        'user_id'     => $newUser->id,
                        'name'        => $request->name,
                        'mobile'      => $request->mobile,
                        'status'      => 'active',
                    ]);
                }
                $connection = ConnectionRequest::where('connection_id',$request->header('connection_id'))->first();
                $connection->update([
                    'user_id'=> $prahari->user_id,
                    'auth_code'=> Str::random(10),
                ]);

                
                return response()->json([
                    'status' => true,
                    'message' => 'Otp Verified Successfully',
                    'data' => [
                        'connection_id' => $connection->connection_id,
                        'auth_code' => $connection->auth_code,
                    ],
                ]);

            }

            

            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP',
                'data' => null,
            ]);


            
           
          
    }

     private function validateConnection( $connectionId)
    {
        $conn = ConnectionRequest::where('connection_id', $connectionId)->exists();
        return $conn;
    }
    private function generateOtp(string $mobile)
    {
        $otp = Otp::updateOrCreate(
            [
                'mobile' => $mobile
            ],
            [
                'otp' => rand(100000, 999999),
            ]
        );
        return $otp;
    }




    
}
