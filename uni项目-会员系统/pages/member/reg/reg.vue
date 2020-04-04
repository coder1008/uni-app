<template>
	<form class='loginView' @submit="register">
		<view class="input-view">
			<view class="label-view">
				<text class="label">账号 </text>
			</view>
			<m-input type="text" focus clearable v-model="username" placeholder="请输入你的邮箱号……"></m-input>
		</view>
		
		<view class="input-view">
			<view class="label-view">
				<text class="label">密码</text>
			</view>
			<m-input type="password" displayable v-model="userpwd" placeholder="请设置登录密码……"></m-input>
		</view>
		
		<view class="input-view">
			<view class="label-view">
				<text class="label">性别</text>
			</view>
			<view class="radio-list">
				<radio-group @change="radio_Change">
				<label class="radio"><radio value="1" checked="true" />男</label>
				<label class="radio"><radio value="0" />女</label>
				</radio-group>
			</view>
		</view>
		
		<view class="button-view">
			<button type="default" class="register" hover-class="hover" formType="submit">注册</button>
		</view>			
	</form>
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
				username: '',
				userpwd: '',
				sex:'',
			}
		},
		methods: {
			radio_Change:function(e){//获取单选按钮的值
				this.sex = e.detail.value;
			},
	
			//uni-app 提交表单方法
			register: function(e) {
				//alert(this.sex);
				//console.log(e.detail.value);
				/**
				 * 客户端对账号信息进行一些必要的校验。
				 */
				if (this.username.length < 5 || !~this.username.indexOf('@')) {
					uni.showToast({
						icon: 'none',
						title: '邮箱地址不合法!'
					});
					return;
				}				
				if (this.userpwd.length < 6) {		
					uni.showToast({
						icon: 'none',
						title: '密码最短为 6 个字符'
					});
					return;

				}

				//获取表单值
				var formData = e.detail.value;
				//this.$api.msg('消息提示');
				//alert('后端API服务器地址: ' + this.$api.server_url);
				//alert(formData.userpwd);
				// 请求
				uni.request({
					//api地址
					url: 'http://appblog.inacorner.top/wp-content/themes/wpApp/api/login.php',
					data: {
						//请求值
					     'usaer_name': formData.username,
						  'uuser_pwd':formData.userpwd
					},
					//请求类型
					method:'POST',
					//请求头
					header: {
						'content-type': 'application/x-www-form-urlencoded', 
					},
					success: (res) => {
						console.log(res.data)
						if(res.data.status==2){
							console.log("注册成功！");
						}else{
							console.log(res)
						}
					}
				});
			}
		}
		
	}
</script>

<style>	
.radio-list{
		flex: 1;
		height: 60upx;
		align-items: center;
		font-size: 0.8rem;
}

.radio{
	padding-right: 50upx;
	color: #424242;
}
</style>
