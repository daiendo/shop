<template>
    <div class="head">
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