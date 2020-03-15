<template>
  <div class="goods">
    <Head title="商品列表">
        <span slot="head-left">
              <Button type="primary" @click="add">添加</Button>
          </span>
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
                    <Button type="info" shape="circle" icon="ios-search" @click="list"></Button>
                </FormItem>
                
            </Form>
    </Head>
    <Table border stripe :columns="columns" :data="data.data"></Table>
    <Modal title="商品详情" v-model="info.is_show" :width="900">
        <Divider orientation="left">基本信息</Divider>
        <table style="width:100%;border-collapse:collapse;text-align:center;font-size:18px;border:1px solid #ddd;" border='1'>
            <tr>
                <th>商品名称</th>
                <th>商品分类</th>
                <th>价格</th>
                <th>库存</th>
            </tr>
            <tr>
                <td>{{info.data.name}}</td>
                <td>{{info.data.class_name}}</td>
                <td>{{info.data.sell_price}}</td>
                <td>{{info.data.stock}}</td>
            </tr>
        </table>
        <Divider orientation="left">商品描述</Divider>
        <Input type="textarea" readonly v-model="info.data.desc" style="width:100%;" :maxRows='6' ></Input>

        <Divider orientation="left">商品图片</Divider>
        <Row :gutter='20'>
            <Col span='6' v-for="(item ,index) in info.data.imgs" :key='index'>
                    <img :src='item' width='100%' style="cursor:pointer" @click="picture.is_show = true;picture.src = item">
            </Col>
        </Row>

        <div slot='footer'>
            <Button type='primary' @click="info.is_show = false">关闭</Button>
        </div>

       
    </Modal>

     <!-- <Modal title="图片查看器" v-model="picture.is_show" :width='500'>
         <img :src="picture.src" alt="" width='100%'>
         <div slot='footer'>
            <Button type='primary' @click="picture.is_show = false">关闭</Button>
        </div>
     </Modal> -->
    
     <PictureView :is_show='picture.is_show' :src='picture.src' @closePicture="picture.is_show=false"></PictureView>

    <Modal :title="operation.title" v-model="operation.is_show" @on-ok ="ok" @on-cancel="cancel">

        <Form :model='form' :label-width='100'>
            <FormItem label="商品名称">
                <Input v-model="form.name"  placeholder="请输入商品名称"></Input>
            </FormItem>
            <FormItem label="商品分类">
                <Select v-model="form.class_id">
                    <Option v-for="(item,index) in category" :value="item.id" :key="index" >
                        {{item.name}}
                    </Option>
                </Select>
            </FormItem>
            <FormItem label="价格">
                <Input type='number' placeholder="请输入价格" v-model="form.sell_price"></Input>
            </FormItem>
            <FormItem label="库存">
                <Input type='number' placeholder="请输入库存" v-model="form.stock"></Input>
            </FormItem>
            <FormItem label="商品描述">
                <Input type='textarea' placeholder="请输入商品描述" v-model="form.desc"></Input>
            </FormItem>
            <FormItem label="上传图片">
                <Upload 
                    ref = 'uploadfile'
                   action="http://localhost/api/public/admin/system/upload"
                   :on-success = 'uploadSuccess'
                   multiple
                >
                    <Button icon="ios-cloud-upload-outline" style="width:100px;"></Button>
                </Upload>
            </FormItem>
        </Form>

    </Modal>

    <Page style="margin-top:10px"
        class='page'
        :total='data.total'
        @on-change='page'
    >
    </Page>
  </div>
</template>

<script>
import Head from '@/components/head.vue'
import PictureView from '@/components/pictureView.vue'
    export default{
        components:{
            Head,
            PictureView
        },
        data(){
            return {
               
                search:{
                    type:'name',
                    value:''
                },
                searchList:[
                    {
                        title:'商品名称',
                        value:'name'
                    },
                    {
                        title:'分类名称',
                        value:'class_name'
                    }
                ],
                info:{
                    is_show:false,
                    data:{}
                },
                operation:{
                    is_show:false,
                    title:''
                },
                form:{
                    id:0,
                    name:'',
                    class_id:0,
                    sell_price:0.00,
                    stock:0,
                    imgs:[],
                    desc:''
                },
                category:{},
                picture:{
                    is_show:false,
                    src:''
                },
                 data:{
                    total:0,
                    data:[]
                },
                columns:[
                    {
                        title:'ID',
                        key:'id',
                        align:'center'
                    },
                    {
                        title:'商品名称',
                        key:'name',
                        align:'center',
                        render:(h,param) =>{
                            return h("a",{
                                on:{
                                    click:()=>{
                                        this.info.is_show = true;
                                        this.detail(param.row.id)
                                    }
                                }
                            },param.row.name)
                        }
                    },
                    {
                        title:'缩略图',
                        key:'thumb',
                        align:'center',
                        render:(h,param) =>{
                            return h("img",{
                                style:{
                                    width:'100%',
                                    cursor:'pointer'
                                },
                                attrs:{
                                    src:param.row.thumb
                                },
                                on:{
                                    click:()=>{
                                        this.picture.is_show = true;
                                        this.picture.src = param.row.thumb;
                                    }
                                }
                            })
                        }
                    },
                    {
                        title:'所属分类',
                        key:'class_name',
                        align:'center'
                    },
                    {
                        title:'价格',
                        key:'sell_price',
                        align:'center'
                    },
                    {
                        title:'库存',
                        key:'stock',
                        align:'center'
                    },
                    {
                        title:'上下架',
                        key:'shelf',
                        align:'center',
                        render:(h,param) =>{
                            return h("i-switch",{
                                props:{
                                    value:param.row.shelf,
                                    size:'small'
                                },
                                on:{
                                    'on-change' : ()=>{
                                        this.changeStatus(param.row.id)
                                    }
                                }
                            })
                        }
                    },
                    {
                        title:'操作',
                        align:'center',
                        render:(h,param)=>{
                            return h("div",[
                                h("Button",{
                                    props:{
                                        type:'error',
                                        size:'small'
                                    },
                                    style:{
                                        margin:'0 5px'
                                    },
                                    on:{
                                        click:()=>{
                                            this.$Modal.confirm({
                                                title:'删除',
                                                content:'确定删除吗',
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
                                        size:'small'
                                    },
                                    style:{
                                        margin:'5px 5px'
                                    },
                                    on:{
                                        click:()=>{
                                            this.operation.is_show = true;
                                            this.operation.title = '修改商品'
                                            this.detail(param.row.id);
                                            this.categoryList()
                                        }
                                    }
                                },'修改')
                            ])
                        }
                    },
                ],
            }
        },
        mounted(){
            this.list()
            // this.categoryList()
        },
        // watch:{
        //     "operation.si_show" (val){
        //         this.categoryList()
        //     }
        // },
        methods:{
            page(page){
                this.list(page)
            },
            add(){
                this.operation.is_show = true;
                this.operation.title = '添加商品';
                  this.categoryList()
                //   console.log(this.form.id)
            },
            uploadSuccess(res){
                if(res.ret == 200){
                    this.form.imgs.push(res.data)
                }else{
                    this.$Message.error(res.msg);
                    return false
                    
                }
            },
            cancel(){
                this.clear()
            },
            async ok(){
                if(this.form.id > 0){
                   await this.update({
                        id:this.form.id,
                        name:this.form.name,
                        class_id:this.form.class_id,
                        sell_price:this.form.sell_price,
                        stock:this.form.stock,
                        imgs:this.form.imgs,
                        desc:this.form.desc
                    })
                }else{
                   
                     await this.insert({
                        name:this.form.name,
                        class_id:this.form.class_id,
                        sell_price:this.form.sell_price,
                        stock:this.form.stock,
                        imgs:this.form.imgs,
                        desc:this.form.desc

                    })
                }
                this.clear()
            },
            clear(){
                this.form = {
                    id:0,
                    name:'',
                    class_id:0,
                    sell_price:0.00,
                    stock:0,
                    imgs:[],
                    desc:''
                };
                this.$refs.uploadfile.clearFiles()
                this.list()
            },
            async list(page = 1){
                let param = {
                    page
                }
                param[this.search.type] = this.search.value;
                let res = await this.api.goods.list(param);
                if(res){
                    this.data = res;
                    // console.log(res)
                }
            },
            async detail(id){
                let res =await this.api.goods.detail({id});
                if(res){
                    this.info.data = res;
                    this.form = res;
                }
            },
            async categoryList(){
               let res = await this.api.category.htmlList()
               
                if(res){
                  
                    this.category = res;
                }
            },
            async insert(form){
                let res = await this.api.goods.insert(form);
                if(res){
                    this.$Message.success('添加成功')
                }
            },
            async update(form){
                let res = await this.api.goods.update(form);
                if(res){
                    this.$Message.success('修改成功')
                }
            },
            async changeStatus(id){
                let res = await this.api.goods.changeStatus({id})
                if(res){
                    this.$Message.success('操作成功')
                }
            },
             async delete(id){
                let res = await this.api.goods.delete({id})
                if(res){
                    this.$Message.success('删除成功')
                }
                this.list()
            },
        }
    }
</script>



<style lang="less" scoped>

</style>