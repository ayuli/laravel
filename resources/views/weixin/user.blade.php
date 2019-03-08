<table border="1">
    <tr>
        <td>用户名</td>
        <td>性别</td>
        <td>时间</td>
    </tr>
    @foreach($user as $v)
    <tr>
        <td>{{$v->nickname}}</td>
        <td>{{$v->sex}}</td>
        <td>{{$v->add_time}}</td>
    </tr>
    @endforeach
</table>
{{ $user->links() }}
