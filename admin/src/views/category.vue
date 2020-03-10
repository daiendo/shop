<template>
  <div class="gategory">
      <Head title='分类列表'>
          <span slot="head-left">
              <Button type="primary" @click="add">添加</Button>
              <Button type="primary" @click="$router.push('/category/tree')" style="margin-left:10px">查看树状图</Button>
              <Button type="primary" style="margin-left:10px" v-if="isChild" @click="$router.go(-1)">返回</Button>
          </span>
          <Form inline slot='head-right'>
                <FormItem>
                    <Input v-model="search.value" placeholder="Enter something..." style="width: 300px" >
                        <Select v-model="search.type" slot="prepend" style="width:140px">
                            <Option v-for='(item ,index) in searchList' :key='index' :value="item.value">
                                {{item.title}}
                            </Option>
                            <!-- <Option v-for="(item,index) in category" :key='index' :value = "item.id">
                                {{item.name}}
                            </Option> -->
                        </Select>
                    </Input>
                </FormItem>
                <FormItem>
                    <Button type="info" shape="circle" icon="ios-search" @click="list"></Button>
                </FormItem>
                
            </Form>
      </Head>
      <Table border stripe :columns="columns" :data="data.data">
    </Table>
    <Modal :title="info.title" v-model="info.is_show" @on-ok='ok'>
        <Form :model ='form' :label-width='100'>
            <FormItem label="上级分类">
                <Select v-model="form.parent_id" >
                    <Option v-for="(item,index) in category" :key='index' :value = "item.id">
                        {{item.name}}
                    </Option>
                </Select>
            </FormItem>
            <FormItem label="分类名称">
                <Input v-model="form.name"></Input>
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
import Head from '@/components/head.vue';
    export default{
        components:{
            Head
        },
        data(){
            return{
                isChild:false,
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
                        title:'分类名称',
                        key:'name',
                        align:'center'
                    },
                    {
                        title:'查看下级',
                        align:'center',
                        render:(h,param)=>{
                            return  h("Button",{
                                props:{
                                    type:'primary',
                                    size:'small'
                                },
                                style:{
                                    margin:'0 5px'
                                },
                                on:{
                                    click:()=>{
                                        this.$router.push({
                                            path:"/category",
                                            query:{
                                                id:param.row.id
                                            }
                                        })
                                    }
                                }
                            },'查看下级')
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
                                        margin:'0 5px'
                                    },
                                    on:{
                                        click:()=>{
                                            this.info.is_show = true;
                                            this.info.title = '修改分类';
                                            this.htmlList()
                                            this.detail(param.row.id)
                                        }
                                    }
                                },'修改')
                            ])
                        }
                    }
                ],
                category:[],
                info:{
                    title:'',
                    is_show:false
                },
                form:{
                    id:0,
                    parent_id:0,
                    name:''
                },
                 search:{
                    value:'',
                    type:'name'
                },
                searchList:[
                    {
                        title:'当前分类搜索',
                        value:'name'
                    }
                ],
            }
        },
        mounted(){
            this.list()
            },
            watch:{
                "$route.query" (val){
                    if(val.id > 0){
                        this.isChild = true
                    }else{
                        this.isChild = false
                    }
                    this.list();
                }
            },
        methods:{
            page(page){
                this.list(page);
            },
            add(){
                this.info.is_show = true;
                this.info.title = '添加分类'
                this.htmlList();
            },
            ok(){
                // console.log(1)
                if(this.form.id > 0){
                    this.update({
                        id:this.form.id,
                       parent_id:this.form.parent_id,
                        name:this.form.name 
                    });
                }else{
                    this.insert({
                        parent_id:this.form.parent_id,
                        name:this.form.name
                    })
                }
                this.list()
                this.clear();
            },
            clear(){
                this.form = {}
            },
            async list(page = 1){
                let param = {
                    page
                }
                if(this.$route.query.id > 0){
                    param['id'] = this.$route.query.id
                }
                param[this.search.type] = this.search.value;
                let res = await this.api.category.list(param)
                if(res){
                    this.data = res;
                }
            },
            async htmlList(){
                let res = await this.api.category.htmlList()
                if(res){
                    this.category = res;
                }
            },
            async insert(form){
                let res = await this.api.category.insert(form)
                if(res){
                    this.$Message.success("添加成功")
                    
                }
                
            },
            async update(form){
                let res = await this.api.category.update(form)
                if(res){
                    this.$Message.success("修改成功")
                    
                }
            },
            async delete(id){
               let res = await this.api.category.delete({id})
                if(res){
                    this.$Message.success("删除成功")   
                }
                this.list()
            },
            async detail(id){
               let res = await this.api.category.detail({id})
                if(res){
                    this.form = res  
                }
                // this.list()
            }
        }
    }
</script>



<style lang="less" scoped>

</style>