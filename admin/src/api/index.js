import ajax from './axios';
let api = {
    user:{
        list:param =>{
            return ajax.post('user/list',param);
        },
        info:id => {
            return ajax.post('user/info',id);
        },
        insert:param =>{
            return ajax.post('user/insert',param);
        },
        delete:id => {
            return ajax.post('user/delete',id);
        },
        update: param => {
            return ajax.post('user/update',param);
        },
        changeStatus:id=>{
            return ajax.post('user/changeStatus',id);
        }
    },
    category:{
        list:param =>{
            return ajax.post('category/list',param);
        },
        treeList:() => {
            return ajax.post('category/treelist');
        },
        htmlList:() => {
            return ajax.post('category/htmllist');
        },
        detail:id => {
            return ajax.post('category/info',id);
        },
        insert:param =>{
            return ajax.post('category/insert',param);
        },
        delete:id => {
            return ajax.post('category/delete',id);
        },
        update: param => {
            return ajax.post('category/update',param);
        }
    },
    goods:{
        list:param =>{
            return ajax.post('goods/list',param);
        },
        detail:id => {
            return ajax.post('goods/detail',id);
        },
        insert:param =>{
            return ajax.post('goods/insert',param);
        },
        delete:id => {
            return ajax.post('goods/delete',id);
        },
        update: param => {
            return ajax.post('goods/update',param);
        },
        changeStatus: id => {
            return ajax.post('goods/changeStatus',id);
        }
    },
    comment:{
        list:param =>{
            return ajax.post('comment/list',param);
        }, 
        delete:id => {
            return ajax.post('comment/delete',id);
        },
    },
    order:{
        list:param =>{
            return ajax.post('order/list',param);
        }, 
        delete:id => {
            return ajax.post('order/delete',id);
        },
        info:id =>{
            return ajax.post('order/info',id);
        }, 
        changeStatus:param => {
            return ajax.post('order/changeStatus',param);
        },
    },
    advert:{
        list:param =>{
            return ajax.post('advert/list',param);
        }, 
        delete:id => {
            return ajax.post('advert/delete',id);
        },
        detail:id =>{
            return ajax.post('advert/info',id);
        }, 
        update:param => {
            return ajax.post('advert/update',param);
        },
        insert:param => {
            return ajax.post('advert/insert',param);
        }
    },
    system:{
        list:param =>{
            return ajax.post('system/list',param);
        }, 
        delete:id => {
            return ajax.post('system/delete',id);
        },
        detail:id =>{
            return ajax.post('system/info',id);
        }, 
        update:param => {
            return ajax.post('system/update',param);
        },
        insert:param => {
            return ajax.post('system/insert',param);
        },
        show:id => {
            return ajax.post('system/show',id);
        },
        getHeader: ()=> {
            return ajax.post('system/getHeader');
        },
    },
    common:{
        login:param =>{
            return ajax.post('common/login',param);
        }, 
        loginInfo:() => {
            return ajax.post('common/loginInfo');
        },
        out:() =>{
            return ajax.post('common/out');
        }, 
        changePass:param => {
            return ajax.post('common/changePass',param);
        },
    }
}
export default {
    install:Vue =>{

        Vue.prototype.api = api
    }
}