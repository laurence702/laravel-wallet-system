<?php

if(!function_exists('formatAsJson')){
    function formatAsJson($status, $message='',$data=[],$meta='',$status_code =''){
        return response()->json([
            'status'=> $status,
            'message'=> $message,
            'data'=> $data,
            'meta'=>$meta
        ],$status_code);
    }
}