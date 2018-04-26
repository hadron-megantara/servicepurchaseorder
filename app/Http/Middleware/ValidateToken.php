<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ValidateToken
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
        try {
            if(!$user = JWTAuth::parseToken()->authenticate()){
                return response()->json(['isError' => true, 'message' => 'Gagal Mendapatkan Data User', 'isResponse' => null]);
            } else{
                $request->attributes->add(['user' => $user]);
            }
        }  catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['isError' => true, 'message' => 'Gagal Mendapatkan Data User', 'isResponse' => null]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e){
            return response()->json(['isError' => true, 'message' => 'Gagal Mendapatkan Data User', 'isResponse' => null]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['isError' => true, 'message' => 'Gagal Mendapatkan Data User', 'isResponse' => null]);
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['isError' => true, 'message' => 'Gagal Mendapatkan Data User', 'isResponse' => null]);
        } catch(\Exception $e){dd($e);
            return response()->json(['isError' => true, 'message' => 'Gagal Mendapatkan Data User', 'isResponse' => null]);
        }

        return $next($request);
    }
}
