@extends('layouts.app')


@section('content')
<button type="button" onclick="apitest()" >apitest</button>

<script>  
    function apitest(){
        $.ajax({  
            url : "http://139.199.8.195/apitest/send",  
            dataType:"jsonp",  
            data:{  
                "id":"1",  
                "name":'CentOS'  
            },  
            type:"post",  
            jsonp:"callback",  
            timeout: 5000,  
            success:function(data){  
                console.log(data);  
            },  
            error:function(){  
                alert("error"); 
            }  
        });  
    }
</script>  
@endsection