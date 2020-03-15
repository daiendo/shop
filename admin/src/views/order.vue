<template>
  <div class="order">
    <FetchList ref = "tableData" :right="24" title="订单列表" :columns="columns" :getData="list"> 
        <Form inline slot='head-right'>
                <FormItem>
                    <Input v-model="search.value" placeholder="Enter something..." style="width: 300px" >
                        <Select v-model="search.type" slot="prepend" style="width:140px">
                            <Option v-for='(item ,index) in searchList' :key='index' :value="item.value">
                                {{item.title}}
                            </Option>
                        </Select>
                    </Input>
                </FormItem>
                <FormItem>
                    <Button type="info" shape="circle" icon="ios-search" @click="$refs.tableData.fetchList()"></Button>
                </FormItem>
            </Form>
    </FetchList>
    <Modal title="修改订单状态" @on-ok="changeStatus" v-model="form.is_show">
        <Select v-model="form.status">
            <Option v-for="(item,index) in form.select" :value="item.status" :key="index">
                {{item.value}}
            </Option>
        </Select>
    </Modal>
    <Modal title="订单详情" v-model="info.is_show" width='1000'>
        <div class="order-title">
            <span >当前订单状态：{{info.data.status_text}}</span>
        </div>

        <Divider orientation="left">基本信息</Divider>
        <table  class="order-table"  border="1" >
            <tr>
                <th>订单号</th>
                <th>订单商品</th>
                <th>下单人</th>
                <th>下单时间</th>
                <th>是否确认</th>
            </tr>
            <tr>
                <td>{{info.data.code}}</td>
                <td>{{info.data.title}}</td>
                <td>{{info.data.username}}</td>
                <td>{{info.data.time}}</td>
                <td>{{info.data.affirm}}</td>
            </tr>
        </table>
        <Divider orientation="left">商品信息</Divider>
        <table class="order-table" border="1">
            <tr>
                <th>商品名称</th>
                <th>所属分类</th>
                <th>数量</th>
                <th>价格</th>
                <th>商品图片</th>
            </tr>
            <tr v-for="(goods,index) in info.data.order_goods" :key="index">
                <td>{{goods.name}}</td>
                <td>{{goods.cate}}</td>
                <td>{{goods.num}}</td>
                <td>{{goods.sell_price}}</td>
                <td><img :src="goods.img" alt="" width="150px" @click="picture.is_show=true;picture.src=goods.img;"></td>
            </tr>
        </table>
        <Divider orientation="left">联系方式</Divider>
        <table class="order-table" border="1">
            <tr>
                <th>联系人</th>
                <th>联系电话</th>
                <th>邮箱</th>
                <th>联系地址</th>
            </tr>
            <tr>
                <td>{{info.data.contact.name}}</td>
                <td>{{info.data.contact.mobile}}</td>
                <td>{{info.data.contact.email}}</td>
                <td>{{info.data.contact.address}}</td>
            </tr>
        </table>
    </Modal>
    <PictureView :is_show='picture.is_show' :src='picture.src' @closePicture="picture.is_show=false"></PictureView>
  </div>
</template>

<script>
import FetchList from '@/components/table.vue'
import PictureView from '@/components/pictureView.vue'
    export default{
        components:{
            FetchList,
            PictureView
        },
        data(){
            return {
                picture:{
                    is_show:false,
                    src:''
                },
                info:{
                    is_show:false,
                    data:{
                        id: 0,
                    code: "",
                    user_id: 0,
                    contact: {},
                    order_goods: [],
                    affirm: "",
                    time: "",
                    is_pay: 0,
                    status: 0,
                    is_delete: 0,
                    username: "",
                    status_text: ""
                    } 
                },
                form:{
                    is_show:false,
                    id:0,
                    status:0,
                    select:[
                        {
                            value:'待收货',
                            status:2
                        },
                        {
                            value:'已取消',
                            status:6
                        }
                    ]
                },
                search:{
                    type:'code',
                    value:''
                },
                searchList:[
                    {
                        title:'订单号',
                        value:'code'
                    },
                    {
                        title:'订单人',
                        value:'username'
                    }
                ],
                columns:[
                    {
                        title:'订单号',
                        key:'code',
                        align:'center',
                        render:(h,param)=>{
                            return h('a',{
                                on:{
                                    click:()=>{
                                        this.info.is_show = true
                                        this.detail(param.row.id)
                                    }
                                }
                            },param.row.code)
                        }
                    },
                    {
                        title:'订单商品',
                        key:'title',
                        align:'center'
                    },
                    {
                        title:'下单人',
                        key:'username',
                        align:'center'
                    },
                    {
                        title:'下单时间',
                        key:'time',
                        align:'center'
                    },
                    {
                        title:'订单状态',
                        key:'affirm',
                        align:'center'
                    },
                    {
                        title:'操作',
                        align:'center',
                        render:(h,param)=>{
                            return h('div',[h('Button',{
                                    props:{
                                        type:'error',
                                        size:'small',
                                        disabled:param.row.status == 6 ? false : true
                                    },
                                    on:{
                                        click:()=>{
                                            this.$Modal.confirm({
                                                title:'删除',
                                                content:'确认删除吗？',
                                                onOk:()=>{
                                                    this.delete(param.row.id)
                                                }
                                            })
                                        }
                                    }
                                },'删除'),
                                h('Button',{
                                    props:{
                                        type:'warning',
                                        size:'small',
                                        disabled:param.row.status > 4 ? true : false
                                    },
                                    style:{
                                        margin:'5px 5px'
                                    },
                                    on:{
                                        click:()=>{
                                            // this.operation.is_show = true;
                                            // this.operation.title = '修改商品'
                                            // this.detail(param.row.id);
                                            // this.categoryList()
                                            this.form.is_show = true
                                            this.form.id = param.row.id
                                        }
                                    }
                                },'修改')
                            ])   
                        }
                    }
                ]
            }
        },
        mounted(){
            this.$refs.tableData.fetchList();
        },
        methods:{
            list(param){
                param[this.search.type] = this.search.value;
                return [this.api.order.list,param]
            },
            async delete(id){
                let res = await this.api.order.delete({id});
                if(res){
                    this.$Message.success('删除成功');
                }
                
                this.$refs.tableData.fetchList();
            },
            async changeStatus(){
                let res = await this.api.order.changeStatus({
                    id:this.form.id,
                    status:this.form.status
                });
                if(res){
                    this.$Message.success('修改成功');
                }
                this.$refs.tableData.fetchList();
            },
            async detail(id){
                let res = await this.api.order.info({id})
                if(res){
                    this.info.data = res;
                    // console.log(res)
                }
            }
        }
    }
</script>



<style lang="less" scoped>
    .order-title{
        width: 100%;
        height: 50px;
        font-size: 15px;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding: 0 15px;
        -ms-flex-pack: justify;
        justify-content: space-between;
        background: #f1f4f6;
        margin-bottom: 10px;

        span{
            color: red;
        }
    }
    .order-table{
       width:100%;
       border-collapse:collapse;
       text-align:center;
       font-size:18px;
       border:1px solid #ddd;
    }
</style>