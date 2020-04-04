<template>
	<view class="picture">
		<view class="tags">
			<block v-for="value in album_data" :key="value.key">
				<view class="tag" @tap="goList(value)">
					<image class="tag-img" :src="value.icon"></image>
					<text class="tag-text">{{value.type}}</text>
				</view>
			</block>
		</view>
			
		<view class="grid">
			<view class="grid-c-06" v-for="item in lists" :key="item.guid">
				<view class="panel" @tap="goDetail(item)">
					<image class="card-img card-list2-img" :src="item.img_src"></image>
					<text class="card-num-view card-list2-num-view">{{item.img_num}}P</text>
					<view class="card-bottm row">
						<view class="car-title-view row">
							<text class="card-title card-list2-title">{{item.title}}</text>
						</view>
						<view @click.stop="share(item)" class="card-share-view"></view>
					</view>
				</view>
			</view>
		</view>
		<text class="loadMore">加载中...</text>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				album_data: [{
						type: '动物',
						id: 1,
						key: 1,
						icon: '../../static/150x150.png'
					},
					{
						type: '风景',
						id: 2,
						key: 2,
						icon: '../../static/150x150.png'
					},
					{
						type: '建筑',
						id: 3,
						key: 3,
						icon: '../../static/150x150.png'
					},
					{
						type: '美女',
						id: 4,
						key: 4,
						icon: '../../static/150x150.png'
					},
					{
						type: '汽车',
						id: 5,
						key: 5,
						icon: '../../static/150x150.png'
					},
					{
						type: '运动',
						id: 6,
						key: 6,
						icon: '../../static/150x150.png'
					},
					{
						type: '动物',
						id: 1,
						key: 7,
						icon: '../../static/150x150.png'
					},
					{
						type: '风景',
						id: 2,
						key: 8,
						icon: '../../static/150x150.png'
					}
				],				
				refreshing: false,
				lists: [],
				fetchPageNum: 1	
			}
		},

		onLoad() {//onLoad是页面的生命周期
			this.getData();
			uni.getProvider({
				service: 'share',
				success: (e) => {
					let data = [];
					for (let i = 0; i < e.provider.length; i++) {
						switch (e.provider[i]) {
							case 'weixin':
								data.push({
									name: '分享到微信好友',
									id: 'weixin'
								});
								data.push({
									name: '分享到微信朋友圈',
									id: 'weixin',
									type: 'WXSenceTimeline'
								});
								break;
							case 'qq':
								data.push({
									name: '分享到QQ',
									id: 'qq'
								});
								break;
							default:
								break;
						}
					}
					this.providerList = data;
				},
				fail: (e) => {
					console.log('获取登录通道失败', e);
				}
			});
		},
		onPullDownRefresh() {
			console.log('下拉刷新');
			this.refreshing = true;
			this.getData();
		},
		onReachBottom() {
			this.getData();
		},
		methods: {
			getData() {
				uni.request({
					url: 'https://unidemo.dcloud.net.cn/api/picture/posts.php?page=' + (this.refreshing ? 1 : this.fetchPageNum) +
						'&per_page=10',
					success: (ret) => {
						console.log(ret)
						if (ret.statusCode !== 200) {
							console.log('请求失败:', ret)
						} else {
							if (this.refreshing && ret.data[0].id === this.lists[0].id) {
								uni.showToast({
									title: '已经最新',
									icon: 'none',
								});
								this.refreshing = false;
								uni.stopPullDownRefresh();
								return;
							}
							let list = [],
								data = ret.data;
							for (let i = 0, length = data.length; i < length; i++) {
								var item = data[i];
								item.guid = this.newGuid() + item.id
								list.push(item);
							}
							console.log('得到list', list);
							if (this.refreshing) {
								this.refreshing = false;
								uni.stopPullDownRefresh()
								this.lists = list;
								this.fetchPageNum = 2;
							} else {
								this.lists = this.lists.concat(list);
								this.fetchPageNum += 1;
							}
						}
					}
				});
			},
			newGuid() {
				let s4 = function() {
					return (65536 * (1 + Math.random()) | 0).toString(16).substring(1);
				}
				return (s4() + s4() + "-" + s4() + "-4" + s4().substr(0, 3) + "-" + s4() + "-" + s4() + s4() + s4()).toUpperCase();
			},
			
			goDetail(e) {//单击转入图片详情页
				console.log( encodeURIComponent(JSON.stringify(e)))
				uni.navigateTo({
					url: '/pages/pic/pic_detail/pic_detail?data=' + encodeURIComponent(JSON.stringify(e))
				})
			},
			
			goList(value) {//转入分类页
				uni.navigateTo({
					url: '/pages/pic/pic_list/pic_list?type=' + value.type + '&id=' + value.id
				})
			},
			
			share(e) {
				if (this.providerList.length === 0) {
					uni.showModal({
						title: '当前环境无分享渠道!',
						showCancel: false
					})
					return;
				}
				let itemList = this.providerList.map(function(value) {
					return value.name
				})
				uni.showActionSheet({
					itemList: itemList,
					success: (res) => {
						uni.share({
							provider: this.providerList[res.tapIndex].id,
							scene: this.providerList[res.tapIndex].type && this.providerList[res.tapIndex].type === 'WXSenceTimeline' ?
								'WXSenceTimeline' : 'WXSceneSession',
							type: 0,
							title: 'uni-app模版',
							summary: e.title,
							imageUrl: e.img_src,
							href: 'https://uniapp.dcloud.io',
							success: (res) => {
								console.log('success:' + JSON.stringify(res));
							},
							fail: (e) => {
								uni.showModal({
									content: e.errMsg,
									showCancel: false
								})
							}
						});
					}
				})
			}
		}
	}
</script>

<style>
/* 	@import '../../style/pic.css'; */
	.grid{
		padding-top: 10px;
	}
</style>
