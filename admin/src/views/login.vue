<template>
  <div class="login">
      <div class="login-con">
        <Card style="width:300px">
            <p slot="title">欢迎登陆</p>
            <Form :model = "form">
                <FormItem prop = "username">
                    <Input type="text" v-model="form.username" placeholder="请输入账号">
                        <Icon type="ios-person-outline" slot="prepend"></Icon>
                    </Input>
                </FormItem>
                <FormItem prop = "password">
                    <Input type="password" v-model="form.password" placeholder="请输入密码">
                        <Icon type="ios-lock-outline" slot="prepend"></Icon>
                    </Input>
                </FormItem>
                <FormItem>
                    <Button type="primary" @click="login" long :lodaing ="loading">signin</Button>
                </FormItem>
            </Form>
        </Card>
      </div>
  </div>
</template>

<script>
    export default{
        data(){
          return {
            loading:false,
            form:{
              username:'',
              password:''
            }
          }
        },
        methods:{
          async login(){
              this.loading = true
              let res = await this.api.common.login(this.form);
              if(res){
                this.$router.push('/')
              }
              this.loading = false
          }
        }
    }
</script>



<style lang="less" scoped>
    .login{
      width: 100%;
      height: 100vh;
      background-image: url('../../public/img/71593.jpg');
      background-size: cover;
      background-position: 50%;
      position: relative;
    }
    .login-con{
        position: absolute;
        right: 160px;
        top: 50%;
        transform: translateY(-60%);
        width: 300px;
    }
</style>