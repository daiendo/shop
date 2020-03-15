
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
                            <Avatar :src='userInfo.head_url'></Avatar>  
                        </MenuItem>
                        <MenuItem name="1">
                            <Dropdown>
                                <a href="javascript:void(0)">
                                    {{userInfo.username}}
                                    <Icon type="ios-arrow-down"></Icon>
                                </a>
                                <DropdownMenu slot="list">
                                    <DropdownItem @click.native="change = true">修改密码</DropdownItem>
                                    <DropdownItem @click.native='out'>注销登录</DropdownItem>
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
                            <MenuItem name="6" :to="{name:'order'}">
                                <Icon type="ios-cart-outline" />
                                订单
                            </MenuItem>
                            <MenuItem name="7" :to="{name:'advert'}">
                                <Icon type="ios-eye-outline" />
                                广告
                            </MenuItem>
                            <MenuItem name="8" :to="{name:'system'}">
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
        <Modal title="修改密码" @on-ok="changePass" v-model="change">
            <Form :model="form">
                <FormItem>
                    <Input type="password" v-model="form.oldpassword" placeholder="请输入旧密码">
                        <Icon type="ios-lock-outline" slot="prepend"></Icon>
                    </Input>
                </FormItem>
                <FormItem>
                    <Input type="password" v-model="form.password" placeholder="请输入新密码">
                        <Icon type="ios-lock-outline" slot="prepend"></Icon>
                    </Input>
                </FormItem>
                <FormItem>
                    <Input type="password" v-model="form.repassword" placeholder="请确认密码">
                        <Icon type="ios-lock-outline" slot="prepend"></Icon>
                    </Input>
                </FormItem>
            </Form>
        </Modal>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                change:false,
                userInfo:{
                    id:0,
                    username:'',
                    head_url:'',
                    login_time:'',
                },
                form:{
                    oldpassword:'',
                    password:'',
                    repassword:''
                },
                header:{
                    title:'',
                    logo:'',
                    keywords:'',
                    description:''
                }
            }
        },
        mounted(){
            this.getInfo();
            this.getHeader();
        },
        computed:{
            
        },
        methods:{
            async getHeader(){
                let res = await this.api.system.getHeader();
                if(res){
                    this.header = res;
                }
            },
            async getInfo(){
                let res = await this.api.common.loginInfo();
                if(res){
                    this.$Message.success('登陆成功');
                    this.userInfo = res;
                }
            },
            async changePass(){
                await this.api.common.changePass(this.form)
                this.clear()
            },
            async out(){
                await this.api.common.out()
            },
            clear(){
                this.form = {
                    oldpassword:'',
                    password:'',
                    repassword:''
                }
            }
        },

        
        metaInfo(){
            return {
                title:this.header.title,
                meta:[
                    {
                        name:'keywords',
                        content:this.header.keywords
                    },
                    {
                        name:'description',
                        content:this.header.description
                    }
                ],
                link:[
                   {
                        href:this.header.logo,
                        rel:'icon',
                        type:'image/x-icon'
                   }
                ]
            }
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