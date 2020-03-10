<template>
  <div class="comment">

        <!-- 非组件化代码 -->
        <!-- <Head title="商品评论" :right='24'>
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
        <Table :data="data.data" :columns='columns' border stripe></Table>
        <Page style="margin-top:10px"
            class='page'
            :total='data.total'
            @on-change='page'
        >
        </Page> -->

        <TableList title="商品评论" :right ='24' :columns='columns' :getData='list1' ref="tableData">
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
            
        </TableList>
    </div>
</template>

<script>
//此组件已经包含在table组件中
// import Head from '@/components/head.vue'


import TableList from '@/components/table.vue'
    export default{
        components:{
            // Head,
            TableList
        },
        data(){
            return {

                //非组件化开发代码
                // data:{
                //     total:0,
                //     data:[]
                // },


                columns:[
                    {
                        title:'用户名',
                        key:'username',
                        align:'center'
                    },
                    {
                        title:'评论的商品名',
                        key:'goods_name',
                        align:'center'
                    },
                    {
                        title:'评论内容',
                        key:'content',
                        align:'center'
                    },
                    {
                        title:'评论时间',
                        key:'time',
                        align:'center'
                    },
                    {
                        title:'操作',
                        align:'center',
                        render:(h,param)=>{
                            return h('Button',{
                                props:{
                                    type:'error',
                                    size:'small'
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
                            },'删除')
                        }
                    }
                ],
                search:{
                    type:'goods_name',
                    value:''
                },
                searchList:[
                    {
                        title:'商品名称',
                        value:'goods_name'
                    },
                    {
                        title:'用户名称',
                        value:'username'
                    }
                ]
            }
        },
        mounted(){
            // this.list();非组件化代码

            //组件化
            this.$refs.tableData.fetchList();

        },
        methods:{

            //非组件化代码
            // page(page){
            //     this.list(page);
            // },
            // async list( page = 1){
            //     let param = {
            //         page
            //     };
            //     param[this.search.type] = this.search.value;

            //     let res = await this.api.comment.list(param);
            //     if(res){
            //         this.data = res;
            //     }
            // },
           

           //组件化
             list1(param){
                param[this.search.type] = this.search.value;
                return [this.api.comment.list,param]
            },


            async delete(id){
                let res = await this.api.comment.delete({id});
                if(res){
                    this.$Message.success('删除成功');
                }
                // this.list();   非组件化开发

                //组件化开发
                this.$refs.tableData.fetchList();
            }
        }
    }
</script>

<style lang="less" scoped>

</style>