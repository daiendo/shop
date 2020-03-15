//缓存
export default {
    //===============sessionStorage 设置缓存============
    /**
     * 
     * @param {string} name 
     * @param {array} data 
     */
    sessionSet(name,data){
        // sessionStorage.removeItem(name);
        this.sessionRemove(name)
        sessionStorage.setItem(name,JSON.stringify(data));
    },
    /**
     * 
     * @param {string} name 
     */
    sessionGet(name){
        return JSON.parse(sessionStorage.getItem(name));
    },
    /**
     * 
     * @param {string} name 
     */
    sessionRemove(name){
        sessionStorage.removeItem(name)
    },

    //===========localStorage  设置缓存=====
    /**
     * 
     * @param {string} name 
     * @param {array} data 
     */
    localSet(name,data){
        // localStorage.removeItem(name);
        this.localRemove(name)
        localStorage.setItem(name,JSON.stringify(data));
    },
    /**
     * 
     * @param {string} name 
     */
    localGet(name){
        return JSON.parse(localStorage.getItem(name));
    },
    /**
     * 
     * @param {string} name 
     */
    localRemove(name){
        localStorage.removeItem(name)
    },

}