<template>
  <div class="categoryTree">
    <Tree :data='tree'></Tree>
  </div>
</template>

<script>
    export default{
        data(){
            return {
                tree:[]
            }
        },
        mounted(){
            this.getTree()
        },
        methods:{
            async getTree(){
                let res = await this.api.category.treeList()
                if(res){
                   
                    this.tree = res;
                }
            }
        }
    }
</script>



<style lang="less" scoped>

</style>