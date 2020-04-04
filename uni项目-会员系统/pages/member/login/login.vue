<template>
	<form class='loginView' @submit="login">
		<view class="input-view">
			<view class="label-view">
				<text class="label">账号 </text>
			</view>
			<input class="input" type="text" focus placeholder="请输入你的邮箱号……" name="nameValue" />
		</view>
		<view class="input-view">
			<view class="label-view">
				<text class="label">密码</text>
			</view>
			<input class="input" type="password" placeholder="请输入登录密码……" name="passwordValue" />
		</view>
		<view class="button-view">
			<button type="default" class="login" hover-class="hover" formType="submit">登录</button>
			<view class="action-row">
				<navigator url="../reg/reg">注册账号</navigator>
				<text>|</text>
				<navigator url="../pwd/pwd">忘记密码</navigator>
			</view>
		</view>		
	</form>
</template>

<script>
	export default {
		data() {
			return {};
		},
		methods: {
			login(e) {
				//this.$api.msg('注册成功');
				//alert('后端API服务器地址: ' + this.$api.server_url);
				
				//处理请求业务				
				console.log("得到账号:"+ e.detail.value.nameValue + ';得到密码:' + e.detail.value.passwordValue)
			}
		}	
	}
</script>

<style>

</style>
