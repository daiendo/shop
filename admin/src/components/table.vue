<template>
    <div class="table">
         <h3>{{title}}</h3>
        <Divider></Divider>
        <Row>
            <Col :span ='left' class='admin-operation'>
                <slot name="head-left"></slot>
            </Col>
            <Col :span ='right' class='admin-search'>
               <slot name="head-right"></slot>
            </Col>
        </Row>
        <Table :data="data.data" :columns='columns' border stripe>
        </Table>
        <Page style="margin-top:10px"
            class='page'
            :total='data.total'
            @on-change='page'
        >
        </Page>
    </div>
</template>

<script>
export default {
    props:{
        title:{
            type:String,
            default:''
        },
        left:{
           type:Number, 
           default:8
        },
        right:{
            type:Number,
            default:16
        },
        columns:{
            type:Array,
            default:[]
        },
        getData:{
            type:Function,
            default:null
        }
    },
    data(){
        return {
            data:{
                total:0,
                data:[]
            }
        }
    },
    methods:{
        page(page){
            this.fetchList(page)
        },
        async fetchList(page = 1){
            if(!this.getData){
                return false
            }
            let param = {
                page
            }
            let res = this.getData(param);

            res = await res[0](res[1]);

            if(res){
                this.data =res
            }else{
                this.data={
                    total:0,
                    data:[]
                }
            }
        }
    }
}
</script>

<style lang="less" scoped>
    
        .admin-operation{
            text-align: left;
        }
        .admin-search{
            text-align: right;
        }
</style>