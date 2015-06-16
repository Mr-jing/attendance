<ul class="list_unstyled">
    <?php $i = 0; ?>
    @forelse($rankings as $uid => $total)
        <li>
            <span class="label label-success">{{$i++}}</span>
            <span class="name">{{$names[$uid]}}</span>
            <span class="time">{{sprintf('%.2f', $total/60)}} min</span>
        </li>
    @empty
        <p>暂无数据</p>
    @endforelse
</ul>
