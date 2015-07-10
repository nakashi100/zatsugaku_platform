$(function(){
    // 画像アップロードボタンを押したら、画像選択できるようにする
    $("#user-form__imgbutton").on("click", function(){
      $(".user-form__img").trigger("click");
    });

    // ファイルが選択されたら画像を変更する 
    $(".user-form__img").on("change", function(){
      var file = $(this).prop('files')[0];
      var fr = new FileReader();
      fr.onload = function() {
          $('#user-form__newimg').attr('src', fr.result);   // 読み込んだ画像データをsrcにセット
      }
      fr.readAsDataURL(file);  // 画像読み込み
    });

    // flashMessageをゆっくり消す
    setTimeout(function(){
      $("#flashMessage").fadeOut("slow");
    }, 1600);

});