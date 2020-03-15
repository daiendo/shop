<template>
  <div class="advert">
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
    <PictureView :is_show='picture.is_show' :src='picture.src' @closePicture="picture.is_show=false"></PictureView>
    <Modal :title="form.title" v-model="form.is_show" @on-ok="ok" @on-cancel="cancel">
        <Form :model="form.data" :label-width="100">
            <FormItem label="标题">
                <Input type='text' placeholder="请输入标题" v-model="form.data.title"></Input>
            </FormItem>
            <FormItem label="链接">
                <Input type='url' placeholder="请输入链接" v-model="form.data.url"></Input>
            </FormItem>
            <FormItem label="位置">
                <Input type='number' placeholder="请输入广告位置" v-model="form.data.pos"></Input>
            </FormItem>
            <FormItem label="上传图片">
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
import PictureView from '@/components/pictureView.vue'
    export default{
        components:{
            FetchList,
            PictureView
        },
        data(){
            return {
                form:{
                    title:'',
                    is_show:false,
                    data:{
                        id:0,
                        title:'',
                        img:'',
                        pos:0,
                        url:''
                    }
                },
                picture:{
                    is_show:false,
                    src:''
                },
                search:{
                type:'title',
                value:''
                },
                searchList:[
                    {
                        title:'广告标题',
                        value:'title'
                    },
                    {
                        title:'广告位置',
                        value:'pos'
                    }
                ],
                columns:[
                    {
                        title:'标题',
                        key:'title',
                        align:'center'
                    },
                    {
                        title:'图片',
                        key:'img',
                        align:'center',
                        render:(h,param)=>{
                            return h('img',{
                                style:{
                                        width:'50%',
                                        cursor:'pointer'
                                    },
                                attrs:{
                                    src:param.row.img
                                    },
                                on:{
                                    click:()=>{
                                        this.picture.is_show = true;
                                        this.picture.src = param.row.img; 
                                    }
                                }
                            },param.row.img)
                        }
                    },
                    {
                        title:'链接',
                        key:'url',
                        align:'center'
                    },
                    {
                        title:'位置',
                        key:'pos',
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
                                                this.form.title = '修改广告'
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
            this.$refs.tableData.fetchList();
        },
        methods:{
            list(param){
                param[this.search.type] = this.search.value;
                return [this.api.advert.list,param]
            },
            add(){
                this.form.is_show = true;
                this.form.title = '添加广告';
            },
            uploadSuccess(res){
                if(res.ret == 200){
                    this.form.data.img = res.data
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
                        title:this.form.data.title,
                        img:this.form.data.img,
                        url:this.form.data.url,
                        pos:this.form.data.pos,
                    })
                }else{
                   
                     await this.insert({
                        title:this.form.data.title,
                        img:this.form.data.img,
                        url:this.form.data.url,
                        pos:this.form.data.pos,

                    })
                }
                this.clear()
            },
            clear(){
                this.form.data = {
                        id:0,
                        title:'',
                        img:'',
                        pos:0,
                        url:''
                    };
                this.$refs.uploadfile.clearFiles()
                this.$refs.tableData.fetchList();
            },
            async info(id){
                let res =await this.api.advert.detail({id});
                if(res){
                    this.form.data = res;
                    // this.form = res;
                }
            },
            async delete(id){
                let res = await this.api.advert.delete({id});
                if(res){
                    this.$Message.success('删除成功');
                }
                
                this.$refs.tableData.fetchList();
            },
            async insert(form){
                let res = await this.api.advert.insert(form);
                if(res){
                    this.$Message.success('添加成功')
                }
            },
            async update(form){
                let res = await this.api.advert.update(form);
                if(res){
                    this.$Message.success('修改成功')
                }
            },
        }
    }
</script>




<style lang="less" scoped>

</style>