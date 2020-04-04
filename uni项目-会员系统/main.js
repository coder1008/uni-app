import Vue from 'vue'
import App from './App'

Vue.config.productionTip = false

const server_url = 'http://localhost/api/';//定义全局后端API服务器地址
const msg = (title, duration=1500, mask=false, icon='')=>{//定义uni-app全局消息提示框
	//统一提示方便全局修改
	if(Boolean(title) === false){
		return;
	}
	uni.showToast({
		title,
		duration,
		mask,
		icon
	});
}
Vue.prototype.$api = {msg, server_url};


App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
