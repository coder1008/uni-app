<template>
	<view class="tabs">
		<scroll-view class="tab-bar">
			<view style="flex-direction: column;">
				<view style="flex-direction: row;">
					<view class="uni-tab-item" v-for="(tab,index) in tabList" :key="tab.id" :id="tab.id" :ref="'tabitem'+index"
					 :data-id="index" :data-current="index" :data-fid="tab.newsid" @click="ontabtap">
						<text class="uni-tab-item-title" :class="tabIndex==index ? 'uni-tab-item-title-active' : ''">{{tab.name}}</text>
					</view>
				</view>
				<view class="scroll-view-indicator">
					<view ref="underline" class="scroll-view-underline" :class="isTap ? 'scroll-view-animation':''" :style="{left: indicatorLineLeft + 'px', width: indicatorLineWidth + 'px'}"></view>
				</view>
			</view>
		</scroll-view>	
	<view class="tab-bar-line"></view>
	
    <view class="media-item" v-for="(item,index) in news_data" :key="index" @tap="opennews" :data-postid="item.post_id">
       <view class="view">
            <text class="media-title" >{{item.title}}</text>
            <view class="image-section flex-row image-section-left">   
				<image  class="image-list1 image-list2"  :src="item.cover"></image>        
			</view>	
        </view>
    </view>
	
 </view>	
</template>

<script>
    export default {
        data() {
            return {
				tabList: [{
					id: "tab01",
					name: '最新',
					newsid: 0
				}, {
					id: "tab02",
					name: '大公司',
					newsid: 23
				}, {
					id: "tab03",
					name: '内容',
					newsid: 223
				}, {
					id: "tab04",
					name: '消费',
					newsid: 221
				}, {
					id: "tab05",
					name: '娱乐',
					newsid: 225
				}],
				news_data: [],
				tabIndex: 0,
				isTap: false,
				indicatorLineLeft: 0,
				indicatorLineWidth: 70,
				index:0,
				refreshing: false,
				pageSize: 5
            };
        },
		
			
     	onLoad() {	
			this.ontabtap();
        },
	
		onPullDownRefresh() {
			console.log('下拉刷新');
			this.refreshing = true;
			this.ontabtap();
		},
		
		onReachBottom(e) {
			this.ontabtap(e);
		},	
	
        methods:{
			ontabtap(e) {//分类
				if(typeof(e) != "undefined"){
					this.index = e.target.dataset.current || e.currentTarget.dataset.current;//获取点击的父ID
					this.tabIndex = this.index;
					this.isTap = true;
					if(this.index == 0){
						this.indicatorLineLeft = 0;
						this.indicatorLineWidth=70;	
					}else if(this.index == 1){
						this.indicatorLineLeft = 70;
						this.indicatorLineWidth=85;	
					}else if(this.index == 2){
						this.indicatorLineLeft = 155;
						this.indicatorLineWidth=70;		
					}else if(this.index == 3){
						this.indicatorLineLeft = 225;
						this.indicatorLineWidth=70;	
					}else{
						this.indicatorLineLeft = 295;
						this.indicatorLineWidth=80;	
					}	
					var fid = e.currentTarget.dataset.fid;	
				}else{
					var fid = 0;	
				}
				
				uni.request({
					url: 'https://unidemo.dcloud.net.cn/api/news?columnId='+ fid +'&minId=0&pageSize='+ this.pageSize +'&column=id,post_id,title,author_name,cover,published_at,comments_count&time=1584952826091',
					method: 'GET',
					data: {},
					success: res => {
						this.news_data = res.data;
						this.pageSize += 5;
								
					},
					fail: () => {},
					complete: () => {}
				});	
			},
			
			
            opennews(e){//新闻详细
                uni.navigateTo({
                    url: '/pages/news/news_detail/news_detail?postid='+e.currentTarget.dataset.postid
                });
            }
        }
    }
</script>

<style>

</style>