import axios from 'axios';
import qs from 'qs';
import iview from 'view-design';
import Storage from '../store/storage.js'
import routes from '../router/router.js';
import Routers from '../router/index'

axios.defaults.baseURL = 'http://localhost/api/public/admin/';
axios.defaults.timeout = 50000;
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=UTF-8';

axios.interceptors.request.use(config =>{
    let token = Storage.sessionGet('token')
    if(config.method == 'post'){
        if(!config.data){
            config.data = {};
        }

        if(token){
            config.data['token'] = token
        }

        config.data = qs.stringify(config.data);
    }else if(config.method == 'get'){
        if(token){
            config.param['token'] = token
        }
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
                if(res.data.data.token){
                    Storage.sessionSet('token',res.data.data.token)
                }
                return res.data.data;
            case 300:
                Storage.sessionRemove('token')
                Routers.push('/login')
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