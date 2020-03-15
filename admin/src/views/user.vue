<template>
  <div id="index">
    <div class="head-warp">
        <Head title="用户列表">
            <Button type="info" @click="add" slot='head-left'>添加用户</Button>
            <Form inline slot='head-right'>
                    <FormItem>
                        <Input v-model="search.value" placeholder="Enter something..." style="width: 300px" >
                            <Select v-model="search.type" slot="prepend" style="width:100px">
                                <Option v-for='(item ,index) in searchList' :key='index' :value="item.value">
                                    {{item.title}}
                                </Option>
                            </Select>
                        </Input>
                    </FormItem>
                    <FormItem>
                        <Button type="info" shape="circle" icon="ios-search" @click="list"></Button>
                    </FormItem>
                    
                </Form>
        </Head>
         <!-- <h3>用户列表</h3>
        <Divider></Divider>
        <Row>
            <Col :span ='6' class='admin-operation'>
                <Button type="info" @click="add">添加用户</Button>
            </Col>
            <Col :span ='18' class='admin-search'>
                <Form inline>
                    <FormItem>
                        <Input v-model="search.value" placeholder="Enter something..." style="width: 300px" >
                            <Select v-model="search.type" slot="prepend" style="width:100px">
                                <Option v-for='(item ,index) in searchList' :key='index' :value="item.value">
                                    {{item.title}}
                                </Option>
                            </Select>
                        </Input>
                    </FormItem>
                    <FormItem>
                        <Button type="info" shape="circle" icon="ios-search" @click="list"></Button>
                    </FormItem>
                    
                </Form>
            
            </Col>
        </Row> -->
          
    </div>
    <Table border stripe :columns="columns1" :data="data1.data">
        <template slot-scope="{ row }" slot="head">
            <!-- <strong>{{ row.username }}</strong> -->
            <img :src='row.head_url' alt="#" width="80px" height="40px" @click="picture.is_show = true;picture.src = row.head_url">
        </template>
        <!-- <template slot-scope="{ row, index }" slot="status">
            <Button :type="btnType" size="small" style="margin-right: 5px" @click="changeUserStatus(row,index)">forbidden</Button>
        </template> -->
        <template slot-scope="{ row}" slot="delete">
            <Button type="warning" size="small" @click="change(row.id)" style="margin:4px">Change</Button>
            <Button type="error" size="small" @click="remove(row.id)" style="margin:4px">Delete</Button>
        </template>
    </Table>
    <PictureView :is_show='picture.is_show' :src='picture.src' @closePicture="picture.is_show=false"></PictureView>
    <Modal :title="info.title" v-model="info.is_show" @on-ok='ok'>
        <Form :model ='form' :label-width='100'>
            <FormItem label="用户名">
                <Input v-model="form.username"></Input>
            </FormItem>
            <FormItem label="密码">
                <Input v-model="form.password" type='password'></Input>
            </FormItem>
            <FormItem label="确认密码">
                <Input v-model="form.repassword" type='password'></Input>
            </FormItem>
            <FormItem label="用户类别">
                <RadioGroup v-model="form.admin">
                    <Radio label="0">
                        <span>用户</span>
                    </Radio>
                    <Radio label="1">
                        <span>管理员</span>
                    </Radio>
                </RadioGroup>
            </FormItem>
            <FormItem label="状态">
                <RadioGroup v-model="form.status">
                    <Radio label="1">
                        <span>启用</span>
                    </Radio>
                    <Radio label="2">
                        <span>禁用</span>
                    </Radio>
                </RadioGroup>
            </FormItem>
            <FormItem label="头像">
                <Upload action="http://localhost/api/public/admin/system/upload"
                :on-success='uploadSuccess'
                ref = 'uploadfile'
                >
                <Button icon="ios-cloud-upload-outline">上传头像</Button>
            </Upload>
            </FormItem>
        </Form>
    </Modal>
    <Page style="margin-top:10px"
        class='page'
        :total='data1.total'
        @on-change='page'
    >

    </Page>
  </div>
</template>

<script>
import Head from '@/components/head.vue';
import PictureView from '@/components/pictureView.vue'
    export default{
        components:{
            Head,
            PictureView
        },
        data (){
            return {
                picture:{
                    is_show:false,
                    src:''
                },
                search:{
                    value:'',
                    type:'username'
                },
                searchList:[
                    {
                        title:'用户名',
                        value:'username'
                    }
                ],
                data1: [],
                info: {
                    title:'',
                    is_show: false
                },
                form:{
                    id:0,
                    username:'',
                    head_url:'',
                    password:'',
                    repassword:'',
                    admin:0,
                    status:1
                },
                columns1: [
                    {
                        title: 'Id',
                        key: 'id',
                        align:'center',
                        width:60
                    },
                    {
                        title: 'Name',
                        key: 'username',
                        align:'center'
                    },
                    {
                        title: 'Head',
                        key: 'head_url',
                        slot: 'head',
                        align:'center'
                    },
                    {
                        title: 'status',
                        key: 'status',
                        // slot: 'status',
                        render : (h,param) =>{
                            let btnType = 'primary';
                            if(param.row.status == 2){
                                btnType= 'default'
                            }
                            return h('Button',{
                                props: {
                                    type : btnType,
                                    size : 'small'
                                },
                                on: {
                                    click: ()=>{
                                        let status_text = 'unlock'
                                        let status = 1
                                        if(param.row.status == 1){
                                            status_text = 'forbidden'
                                            status = 2
                                            // console.log(this.style)
                                        }
                                        this.$Modal.confirm({
                                            title:'提示',
                                            content:'确定要' + status_text + '吗？',
                                            onOk: ()=>{
                                                this.changeUserStatus(param.row.id)
                                            }
                                        })
                                    }
                                }
                            },param.row.status ==1 ? 'forbidden' : 'unlock')
                        },
                        align:'center'
                    },
                    {
                        title: 'role',
                        key: 'admin_text',
                        align:'center',
                        width:80
                    },
                    {
                        title: 'time',
                        key: 'time',
                        align:'center'
                    },
                    {
                        title: 'delete',
                        key: 'is_delete',
                        slot: 'delete',
                        align:'center'
                    }
                ],
                
                
            }
        },
        mounted(){
            this.list()
            
        },
        methods:{
            page(num){
                this.list(num);
            },
           async list(page = 1){
               let param ={
                   page:page,
               }
               param[this.search.type] = this.search.value
               let res = await this.api.user.list(param);
               if(res){
                   this.data1 = res;
                //    console.log(res.total)
                //    this.total = res.total
                //    console.log(this.data1.total)
               }
            },
           async changeUserStatus (id) {
               let res = await this.api.user.changeStatus({id});
               if(res){
                   this.list()
               }
              
            },
            async remove(id){

                let res = await this.api.user.delete({id})
                if(res){
                   this.list() 
                }
            },
            async insert(form){
                let res = await this.api.user.insert(form)
                if(res){
                    this.list()
                   
                }
                 this.clear()
            },
            async detail(id){
                let res = await this.api.user.info({id})
                if(res){
                    this.form = res
                    
                }
            },
            async update(form){
                let res = await this.api.user.update(form)
                if(res){
                    this.list()
                   
                }
                 this.clear()
            },
            clear(){
                this.form = {}
                this.$refs.uploadfile.clearFiles()
            },
            add(){
                this.info.title = '添加'
                this.info.is_show = true
            },
            ok(){
                
                if(this.form.id > 0){
                    this.update(this.form)
                }else{
                    
                    this.insert({
                      username:this.form.username,
                      head_url:this.form.head_url,
                      admin:this.form.admin,
                      status:this.form.status,
                      repassword:this.form.repassword,
                      password:this.form.password
                    })
                }
            },
            uploadSuccess(res){
                
                if(res.ret == 200){
                    this.form.head_url = res.data
                    // console.log(form)
                }
            },
            change(id){
                this.info.is_show=true;
                this.info.title = '修改'
                // console.log(id)
                this.form.id = id
                this.detail(id)
            }
        }
    }
</script>



<style lang="less" scoped>
    #index{
   
        .admin-operation{
            text-align: left;
        }
        .admin-search{
            text-align: right;
        }
    }
</style>