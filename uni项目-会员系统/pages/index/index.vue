<template>
		<view class="swipers">
				<view class="swipers-bar">
					<!-- 一组幻灯片代码开始，用到组件swiper -->
					<!-- indicator-dots autoplay interval……：组件相关属性，具体可以查看官网说明 -->
					<swiper class="swiper" indicator-dots="indicatorDots" autoplay="autoplay" interval="interval" duration="duration">
						<swiper-item v-for="(item , index) in homeSlide" :key="index">
							<!-- uni img组件 src绑定值为服务端返回的数据中的文章缩略图 -->			
							<image :src="item.img" mode="aspectFill"></image>
						</swiper-item>
					</swiper>
				</view>
		</view>
</template>

<script>
	export default {
		data() {
			return {
				homeSlide: [] ,
			}
		},
		onLoad() {//页面载入即执行
			this.getHomeSlideFunc();
		},
		
		methods: {
			//获取幻灯片数据
			getHomeSlideFunc() {
				// 用uniapp的request发起请求
				uni.request({
					url: 'http://appblog.inacorner.top/wp-content/themes/wpApp/api/homeSlide.php',//接口地址
					success: (res) => {
						// 请求成功之后将幻灯片数据赋值给homeSlide
						this.homeSlide=res.data.post;
					}
				});
			},
			
		}
	}
</script>

<style>
	view {
		display: block;
	}

	.swipers {
		flex: 1;
		flex-direction: column;
		overflow: hidden;
		background-color: #ffffff;
		/* #ifdef MP-ALIPAY || MP-BAIDU */
		height: 100vh;
		/* #endif */
	}
		
	.swipers-bar {
		width: 750upx;
		height: 84upx;
		flex-direction: row;
		white-space: nowrap;
	}
		
	swiper-item {
		background-color: #f8f8f8;
	}
	
	swiper-item image{
		width: 100%;
	}
	
</style>
