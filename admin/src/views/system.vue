<template>
  <div class="system">
    <FetchList title="广告列表" :columns="columns" :getData='list' ref="tableData">
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
                <Button type="info" shape="circle" icon="ios-search" @click="$refs.tableData.fetchList()"></Button>
            </FormItem>
            
        </Form>

    </FetchList>
    <Modal :title="form.title" v-model="form.is_show" @on-ok="ok" @on-cancel="cancel">
        <Form :model="form.data" :label-width="100">
            <FormItem label="标题">
                <Input type='text' placeholder="请输入标题" v-model="form.data.title"></Input>
            </FormItem>
            <FormItem label="关键字">
                <Input type='textarea' placeholder="请输入关键字" v-model="form.data.keywords" :row="3"></Input>
            </FormItem>
            <FormItem label="描述">
                <Input type='textarea' placeholder="请输入描述" v-model="form.data.description" :row="6"></Input>
            </FormItem>
            <FormItem label="logo">
                <Upload 
                    ref = 'uploadfile'
                   action="http://localhost/api/public/admin/system/upload"
                   :on-success = 'uploadSuccess'
                   
                >
                    <Button icon="ios-cloud-upload-outline" style="width:100px;"></Button>
                </Upload>
            </FormItem>
        </Form>
    </Modal>
  </div>
</template>

<script>
import FetchList from '@/components/table.vue';
    export default{
        components:{
            FetchList,
        },
        data(){
            return {
                total:0,
                form:{
                    title:'',
                    is_show:false,
                    data:{
                        id:0,
                        title:'',
                        keywords:'',
                        description:'',
                        logo:''
                    }
                },
                
                search:{
                type:'title',
                value:''
                },
                searchList:[
                    {
                        title:'网站标题',
                        value:'title'
                    }
                ],
                columns:[
                    {
                        title:'网站标题',
                        key:'title',
                        align:'center'
                    },
                    {
                        title:'logo',
                        key:'logo',
                        align:'center',
                        render:(h,param)=>{
                            return h('img',{
                                style:{
                                        width:'50%',
                                        cursor:'pointer'
                                    },
                                attrs:{
                                    src:param.row.logo
                                    },
                            },param.row.logo)
                        }
                    },
                    {
                        title:'关键字',
                        key:'keywords',
                        align:'center'
                    },
                    {
                        title:'网站描述',
                        key:'description',
                        align:'center'
                    },
                    {
                        title:'创建时间',
                        key:'create_time',
                        align:'center'
                    },
                    {
                        title:'是否显示',
                        key:'status',
                        align:'center',
                        render:(h,param)=>{
                            return h("i-switch",{
                                props:{
                                    value:param.row.status,
                                    size:'small',
                                    trueValue:1,
                                    falseValue:0,
                                    disabled:this.total == 1 ? true : false
                                },
                                on:{
                                    "on-change":()=>{
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
                                return h('div',[h('Button',{
                                        props:{
                                            type:'error',
                                            size:'small',
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
                                        },
                                        style:{
                                            margin:'5px 5px'
                                        },
                                        on:{
                                            click:()=>{
                                                this.form.is_show = true
                                                this.form.title = '修改网站信息'
                                                this.form.data.id = param.row.id
                                                this.info(param.row.id)
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
            this.$refs.tableData.fetchList().then(res=>{
                if(res){
                    this.total = res.total;
                }
            });
        },
        methods:{
            list(param){
                param[this.search.type] = this.search.value;
                return [this.api.system.list,param]
            },
            add(){
                this.form.is_show = true;
                this.form.title = '添加网站信息';
            },
            uploadSuccess(res){
                if(res.ret == 200){
                    this.form.data.logo = res.data
                }else{
                    this.$Message.error(res.msg);
                    return false
                    
                }
            },
            cancel(){
                this.clear()
            },
            async ok(){
                if(this.form.data.id > 0){
                   await this.update({
                        id:this.form.data.id,
                        keywords:this.form.data.keywords,
                        description:this.form.data.description,
                        logo:this.form.data.logo,
                        title:this.form.data.title,
                    })
                }else{
                   
                     await this.insert({
                        keywords:this.form.data.keywords,
                        description:this.form.data.description,
                        logo:this.form.data.logo,
                        title:this.form.data.title,

                    })
                }
                this.clear()
            },
            clear(){
                this.form.data = {
                        id:0,
                        title:'',
                        keywords:'',
                        description:'',
                        logo:''
                    };
                this.$refs.uploadfile.clearFiles()
                this.$refs.tableData.fetchList();
            },
            async info(id){
                let res =await this.api.system.detail({id});
                if(res){
                    this.form.data = res;
                }
            },
            async delete(id){
                let res = await this.api.system.delete({id});
                if(res){
                    this.$Message.success('删除成功');
                }
                
                this.$refs.tableData.fetchList().then(res=>{
                if(res){
                    this.total = res.total;
                }
            });
            },
            async insert(form){
                let res = await this.api.system.insert(form);
                if(res){
                    this.$Message.success('添加成功')
                }
            },
            async update(form){
                let res = await this.api.system.update(form);
                if(res){
                    this.$Message.success('修改成功')
                }
            },
            async changeStatus(id){
                let res =await this.api.system.show({id});
                if(res){
                    this.$Message.success('操作成功')
                }
                this.$refs.tableData.fetchList();
            }
        }
    }
</script>




<style lang="less" scoped>

</style>