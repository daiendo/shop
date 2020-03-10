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
    }
}
export default {
    install:Vue =>{

        Vue.prototype.api = api
    }
}