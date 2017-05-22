<?php

namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Closure;
use App\ReturnData; //class object for return data
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Input;

class HeaderAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $retData = new ReturnData;
        //access token empty

        if(!Input::header('access_token'))
        {
            $retData->set(__('api.access_token_empty'),406,[]);
            return response()->json($retData,$retData->code);
        }else{
            $data = $this->validate_JWT($request->header('access_token'));
            if($data == "error")
            {
                $retData->set(__('api.access_token_error'),406,$data);
                return response()->json($retData,$retData->code);
            }else{
                //check if user send user id
                if(!Input::header('id') || !Input::header('email'))
                {
                   return $retData->set(__('api.validation_error'),406,array("message"=>__('api.access_token_error')));
                }else{
                    $jwtData = $data->data;
                   if($jwtData->user_id == intval($request->header('id')) && $jwtData->email == $request->header('email'))
                   {
                        //validation success, next
                        return $next($request);
                       
                   }else{
                        $retData->set(__('api.access_token_error'),406,array("message"=>__('api.access_token_error')));
                        return response()->json($retData,$retData->code);
                   }
                }
            }
        }
        
    }

    public function validate_JWT($jwt)
    {

        $key = env('JWT_SECRET','no kes');
        
        try{
            $decoded = JWT::decode($jwt, $key, array('HS256'));
        }catch(\Firebase\JWT\SignatureInvalidException $e)
        {
            return "error";
        }catch (\DomainException $e)
        {
            return "error";
        }catch (\UnexpectedValueException $e)
        {
            return "error";
        }catch(\FatalErrorException $e)
        {
            return "error";
        }catch(\InvalidArgumentException $e)
        {
            return "error";
        }

        return $decoded;
    }
}
