$(document).ready(function() {
  $("#register0").change(function() { //咨询方式 //bulr 点击触发_失去焦点  change直接触发_改变值
    $("#userinfozxpd").html("");
    $("#userinfobzpd").html("");
    $.ajax({
      type: "post",
      url: "{$Think.const.DQURL}Addzixun/ZiXunLianDong",
      data: "username=" + $("#register0").val(),
      success: function(msg) {
        $("#userinfo0").html(msg);
      }
    });
  });
  $("#register0").change(function() { //所属咨询 //bulr 点击触发_失去焦点  change直接触发_改变值
    $.ajax({
      type: "post",
      url: "{$Think.const.DQURL}Addzixun/zixunyuanLianDong",
      data: "zixunyuan=" + $("#register0").val(),
      success: function(msg) {
        $("#userinfoZxy").html(msg);
      }
    });
  });
  $("#register0").change(function() { //所属病种 //bulr 点击触发_失去焦点  change直接触发_改变值
    $.ajax({
      type: "post",
      url: "{$Think.const.DQURL}Addzixun/bzLianDong",
      data: "bzid=" + $("#register0").val(),
      success: function(msg) {
        $("#userinfoBz").html(msg);
      }
    });
  });
});