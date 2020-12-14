
window.onload = function() {
    history.pushState({}, "", "");

}

function openNav() {
    document.getElementById("mySidenav").style.width = "320px";

}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

function Sharesomething() {
    let imageavt = document.getElementById("Streamimage");
    let input = document.getElementById("Streaminput");
    let button3 = document.getElementById("Streambutton");
    imageavt.style.display = "none";
    button3.style.display = "block";
    input.style.height = "130px";

}
function closethebuttton(){
    let imageavt = document.getElementById("Streamimage");
    let input = document.getElementById("Streaminput");
    let button3 = document.getElementById("Streambutton");
    let thanhtext = document.getElementById("Streaminput");
    imageavt.style.display = "block";
    button3.style.display = "none";
    input.style.height = "90%";

}
function delete_cmt(id) {

    option = confirm('Bạn có muốn xoá bình luận này không')
    if (!option) {
        return;
    }
    $.post('./xoa/delete_cmt.php', {
        'id': id
    }, function (data) {

        location.reload()
    })

}
function delete_tb(id) {
    option = confirm('Bạn có muốn xoá thông báo này không')
    if (!option) {
        return;
    }
    $.post('./xoa/delete_tb.php', {
        'id': id
    }, function (data) {
        location.reload()
    })

}
function edit_tb(id) {
    $noidung="#noidung"+id;
    $file="#file"+id;
    $.post('./edit/edit_tb.php', {
        id_tb : id,
        noidung : $($noidung).val(),
        file : $($file).val(),
    }, function (data) {
        location.reload()
    })

}

function addcl(){
    let parent = this.parentNode.parentNode;
    parent.classList.add("focus");
}

function remcl(){
    let parent = this.parentNode.parentNode;
    if(this.value == ""){
        parent.classList.remove("focus");
    }
}

$(document).ready(function () {
    $('.edit_data').on('click', function () {
        var class_id = $(this).attr("id");
        $.ajax({
            url:"./thaotacvoiclass/get_class_info.php",
            method: "POST",
            data: {"class_id":class_id},
            dataType: "JSON",
            success:function (data) {
                $('#class_id').val(data.ma_lophoc);
                $('#classname').val(data.ten_lophoc);
                $('#subject').val(data.ten_monhoc);
                $('#room').val(data.ten_phonghoc);

                $('#editModal').modal('show');
            }
        });
    });

    $('.delete_data').on('click', function () {
        var class_id = $(this).attr("id");
        var delete_msg = document.getElementById('delete_msg');

        $.ajax({
            url:"./thaotacvoiclass/get_class_info.php",
            method: "POST",
            data: {"class_id":class_id},
            dataType: "JSON",
            success:function (data) {
                $('#deleteModal').modal('show');
                delete_msg.innerHTML = "Do you want to Remove class " + data.ten_lophoc + " ??";
                $('#delete_id').val(class_id);

            }
        });
    });

    $('.unenroll').on('click', function () {
        var class_id = $(this).attr("id");
        var delete_msg = document.getElementById('delete_msg');

        $.ajax({
            url:"../giangvien/thaotacvoiclass/get_class_info.php",
            method: "POST",
            data: {"class_id":class_id},
            dataType: "JSON",
            success:function (data) {
                $('#deleteModal').modal('show');
                delete_msg.innerHTML = "Do you want to Unenroll class " + data.ten_lophoc + " ??";
                $('#delete_id').val(class_id);
            }
        });
    });
    // xoa account manage account admin

    const inputs = document.querySelectorAll(".input_login");

    inputs.forEach(input => {
        input.addEventListener("focus", addcl);
        input.addEventListener("blur", remcl);
    });



    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });


    $('.delete_data').on('click', function () {
        var username = $(this).attr("id");
        var delete_msg = document.getElementById('delete_msg');
        var name = document.getElementById(username).textContent;
        $('#deleteModal').modal('show');
        delete_msg.innerHTML = "Do you want to Remove student " + name + " ??";
        $('#delete_id').val(username);
    });

});

/*-----------------*/




