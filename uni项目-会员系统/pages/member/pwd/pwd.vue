<template>
	<view class='loginView'>
		<view class="input-view">
			<view class="label-view">
				<text class="label">邮箱 </text>
			</view>
			<m-input type="text" focus clearable v-model="email" placeholder="请输入邮箱……"></m-input>
		</view>
		<view class="button-view">
			<button type="primary" class="primary" @tap="findPassword">提交</button>
		</view>		
	</view>
</template>

<script>
	/* 导入插件*/
	import mInput from '../../../components/m-input.vue';
	
	export default {
		components: {//注册插件组件
			mInput
		},
				
		data() {
			return {
				email: ''
			};
		},
		methods: {
			findPassword() {
				/**
				 * 仅做示例
				 */
				if (this.email.length < 3 || !~this.email.indexOf('@')) {
					uni.showToast({
						icon: 'none',
						title: '邮箱地址不合法',
					});
					return;
				}
				
				uni.showToast({
					icon: 'none',
					title: '已发送重置邮件至注册邮箱，请注意查收。',
					duration: 3000
				});
			}
		}	
	}
</script>

<style>

</style>
