<?php

use App\Constants\Status_Responses;


if(!function_exists("jsonResponse")){
    function jsonResponse($payLoad){
        return response([
            'status_code' => $payLoad['statusCode'],
            'message' => $payLoad['message'],
            'data' => $payLoad['data'],
        ], $payLoad['statusCode']);
    }
 }

 if(!function_exists("jsonPayLoad")){
    function jsonPayLoad($statusCode, $message, $data = [] ,$ok = true ){
        $payLoad = [
            'statusCode' => $statusCode,
            'ok' => $ok,
            'message' => $message,
            'data' => $data
        ];

        return jsonResponse($payLoad);
    }
 }

 if(!function_exists("response_msg")){
    function response_msg($status_code) {
        return Status_Responses::get_response_msg($status_code);
    }
 }

 if(!function_exists("forbidden_response")){
    function forbidden_response($msg = null){
        $msg = $msg ?? response_msg(Status_Responses::FORBIDDEN);
        return jsonPayLoad(Status_Responses::FORBIDDEN, $msg);
    }
 }

 if(!function_exists("created_response")){
    function created_response($data = [], $msg = null){
        $msg = $msg ?? response_msg(Status_Responses::CREATED);
        return jsonPayLoad(Status_Responses::CREATED, $msg, $data);
    }
 }


  
 if(!function_exists("success_response")){
    function ok_response($data = [], $msg = null){
        $msg = $msg ?? response_msg(Status_Responses::OK);
        return jsonPayLoad(Status_Responses::OK, $msg, $data);
    }
 }

 if(!function_exists("ok_response")){
    function ok_response($data = [], $msg = null){
        $msg = $msg ?? response_msg(Status_Responses::OK);
    
        return jsonPayLoad(Status_Responses::OK, $msg, $data);
    }
 }
if(!function_exists("ok_response_pagination")){
    function ok_response_pagination($data,$pagination,$totalItems){
        $msg =  response_msg(Status_Responses::OK);
        return  [
            'status_code' => Status_Responses::OK,
            'message' => $msg,
            'data' => $data,
            'total_items' => $totalItems,
            'pagination' => generatePaginationLinks($pagination),
        ];
       // return jsonPayLoad(Status_Responses::OK, $msg, $data);
    }
}

 if(!function_exists("unauthorized_response")){
    function unauthorized_response($msg = null){
        $msg = $msg ?? response_msg(Status_Responses::UNAUTHORIZED);
        return jsonPayLoad(Status_Responses::UNAUTHORIZED, $msg);
    }
 }

 if(!function_exists("unprocessable_response")){
    function unprocessable_response($errors = []){
        return jsonPayLoad(Status_Responses::UNPROCESSABLE_ENTITY, response_msg(Status_Responses::UNPROCESSABLE_ENTITY), $errors);
    }
 }

 if(!function_exists("not_found_response")){
    function not_found_response(){
        return jsonPayLoad(Status_Responses::NOT_FOUND, response_msg(Status_Responses::NOT_FOUND));
    }
 }

 if(!function_exists("not_found_response_msg")){
    function not_found_response_msg($msg){
        return jsonPayLoad(Status_Responses::NOT_FOUND, $msg);
    }
 }

 
 if(!function_exists("internal_server_error_response")){
    function internal_server_error_response(){
        return jsonPayLoad(Status_Responses::INTERNAL_SERVER_ERROR, response_msg(Status_Responses::INTERNAL_SERVER_ERROR));
    }
 }


if(!function_exists("bad_request")){
    function bad_request($errors = [] ,$msg = null){
        return jsonPayLoad(Status_Responses::BAD_REQUEST, $msg,$errors);
    }
}