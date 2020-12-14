public static void main(String[] args) {
		String url = "http://www.youdao.com/smartresult-xml/search.s?" +
				     "jsFlag=true&type=mobile&q=13985046628";
		String result = callUrlByGet(url,"GBK");
		System.out.println(result);   
	}

$(document).ready(function(){ // //bulr 点击触发_失去焦点  change直接触发_改变值
$("#sjjc").change(function(){ 
var reg = /^(13|15|18|17)[0-9]{9}$/;
tel=$("#sjjc").val();
if(reg.test(tel)){
ajax();
}
});
});