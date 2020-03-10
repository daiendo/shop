import axios from 'axios';
import qs from 'qs';
import iview from 'view-design';
axios.defaults.baseURL = 'http://localhost/api/public/admin/';
axios.defaults.timeout = 50000;
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=UTF-8';

axios.interceptors.request.use(config =>{
    if(config.method == 'post'){
        if(!config.data){
            config.data = {};
        }
        config.data = qs.stringify(config.data);
    }else if(config.method == 'get'){

    }
    return config;
},error =>{
    return Promise.reject(error);
});

axios.interceptors.response.use(res=>{
   if(typeof res.data =='string'){
       alert(res.data);
       return false;
   }else{
       switch(res.data.ret){
            case 200:
                return res.data.data;
            case 300:
                //code...
                break;
            default:
                iview.Message.error(res.data.msg);
                return false; 
       }
   }
},error =>{
    return Promise.reject(error);
})

export default axios;