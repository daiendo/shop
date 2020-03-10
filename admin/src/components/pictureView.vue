<template>
  
    <Modal title="图片查看器" v-model="show" :width='500'>
         <img :src="src" alt="" width='100%'>
         <div slot='footer'>
            <Button type='primary' @click="showPic">关闭</Button>
        </div>
     </Modal>
 
</template>

<script>
    export default{
        props:{
            is_show:{
                type:Boolean,
                default:false
            },
            src:{
                type:String,
                default:''

            }
        },
        data(){
            return {
                show:false
            }
        },
        watch:{
            is_show(val){
                // console.log(val)
                if(val){
                    
                    this.show = true;
                    this.$emit('closePicture')
                }
                // this.$emit('closePicture')
            }
        },
        methods:{
            showPic(){
                this.show = false
                
            }
        }
    }
</script>



<style lang="less" scoped>

</style>