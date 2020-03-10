
<template>
    <div class="layout">
        <Layout>
            <Header>
                <Menu mode="horizontal" theme="dark" active-name="1">
                    <div class="layout-logo">
                        <router-link to = '/'>
                            <img src="../assets/logo-v2.0.0.png" alt="" width="100px" height="30px">
                        </router-link>
                    </div>
                    <div class="layout-nav">
                         <MenuItem name="2">
                            <Avatar></Avatar>  
                        </MenuItem>
                        <MenuItem name="1">
                            <Dropdown>
                                <a href="javascript:void(0)">
                                    下拉菜单
                                    <Icon type="ios-arrow-down"></Icon>
                                </a>
                                <DropdownMenu slot="list">
                                    <DropdownItem>修改密码</DropdownItem>
                                    <DropdownItem>注销登录</DropdownItem>
                                </DropdownMenu>
                            </Dropdown>
                        </MenuItem>
                       
                    </div>
                </Menu>
            </Header>
            <Layout>
                <Sider hide-trigger :style="{background: '#fff'}" >
                    <div>
                        <Menu  theme="light" width="auto" ref='activePre' >
                            <!-- <router-link > -->
                                <MenuItem name="1"  to="/">
                                <Icon type="ios-home-outline" />
                                    首页 
                                </MenuItem>
                            <!-- </router-link> -->
                        
                                <MenuItem name="2" :to="{name:'user'}">
                                <!-- <router-link :to="{name:'user'}"> -->
                                <Icon type="ios-people-outline" />
                                    用户
                                    <!-- </router-link> -->
                                </MenuItem>
                            
                            <MenuItem name="3" :to="{name:'category'}">
                                <Icon type="ios-albums-outline" />
                                分类
                            </MenuItem>
                            <MenuItem name="4" :to="{name:'goods'}">
                                <Icon type="ios-basket-outline" />
                                商品
                            </MenuItem>
                            <MenuItem name="5" :to="{name:'comment'}">
                                <Icon type="ios-calendar-outline" />
                                评论
                            </MenuItem>
                            <MenuItem name="6" :to="{name:'user'}">
                                <Icon type="ios-cart-outline" />
                                订单
                            </MenuItem>
                            <MenuItem name="7" :to="{name:'user'}">
                                <Icon type="ios-eye-outline" />
                                广告
                            </MenuItem>
                            <MenuItem name="8" :to="{name:'user'}">
                                <Icon type="md-link" />
                                系统
                            </MenuItem>
                        </Menu>
                    </div>
                </Sider>
                <Layout :style="{padding: '0 24px 24px'}">
                    <Content :style="{padding: '24px', minHeight: '280px', background: '#fff'}">
                        <router-view ></router-view>
                    </Content>
                </Layout>
            </Layout>
            <Footer>copyright 2019 &copy; daiend 陇ICP证18002472号</Footer>
        </Layout>
    </div>
</template>
<script>
    export default {
        data(){
            return {
            }
        },
        mounted(){

        },
        computed:{
            
        },
        methods:{
            // change:function(){ 
            //     console.log(1)
            //     let eleArr =this.$refs.activePre.$children
            //     for(let i=0;i<eleArr.length;i++){
            //         console.log(eleArr[i].active)
            //         if(eleArr[i].active == true){
            //         this.activeNa = eleArr[i].name;
            //         console.log(this.$refs.activePre.$children)
            //         }
            //     }
            // }
        }
    }
</script>
<style lang='less' scoped>
.layout{
    border: 1px solid #d7dde4;
    background: #f5f7f9;
    position: relative;
    border-radius: 4px;
    overflow: hidden;
    
        .layout-logo{
        width: 100px;
        height: 30px;
        background: #5b6270;
        border-radius: 3px;
        float: left;
        position: relative;
        top: 15px;
        left: 20px;
            img{
                vertical-align: top;
            }
        }
        .layout-nav{
            width: 420px;
            margin: 0 auto;
            margin-right: 20px;
                .ivu-menu-item{
                float: right;
                    .ivu-dropdown a{
                        color: #fff;
                    }
                }
        }
}

</style>