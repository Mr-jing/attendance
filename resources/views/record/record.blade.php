@extends('layout.default')


@section('title')记录
@stop


@section('content');
<div class="container" style="margin-top: 50px;">
    <h2>考勤记录（第二天凌晨更新）：</h2>

    <form class="form-inline" style="margin: 10px 0;">
        <select class="form-control" id="select-year">
            <option value="2015">2015</option>
        </select>
        <span>年</span>
        <select class="form-control" id="select-month">
            <?php for ($index = 1; $index <= 12; $index++) : ?>
            <?php if ($month == $index): ?>
            <option value="<?= $index ?>" selected="selected"><?= $index ?></option>
            <?php else: ?>
            <option value="<?= $index ?>"><?= $index ?></option>
            <?php endif; ?>
            <?php endfor; ?>
        </select>
        <span>月</span>
    </form>
    <?php if (empty($records)): ?>
    <p>暂无记录</p>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <th>日期</th>
                <th>首次打卡时间</th>
                <th>末次打卡时间</th>
                <th>状态</th>
                <th>是否休息日</th>
                <th>工作时长（分钟）</th>
                <th>加班时长（分钟）</th>
            </tr>
            <?php foreach ($records as $record): ?>
            <tr class="<?= cssStatus($record['status']); ?>">
                <td><?= $record['day'] ?></td>
                <td><?= $record['start_time'] ?></td>
                <td><?= $record['end_time'] ?></td>
                <td><?= getFriendlyStatus($record['status']); ?></td>
                <td><?= $record['is_holiday'] ? '是' : '否'; ?></td>
                <td><?= intval($record['work_time'] / 60) ?></td>
                <td><?= intval($record['overtime'] / 60) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <h2>是否全勤：<?= $allTsutomu ?></h2>

    <h2>加班申请模板：</h2>

    <form>
                    <textarea class="form-control" rows="10">
                        <?php
                        foreach ($overtimeRecords as $record) :
                        $month = sprintf('%02d', $record['month']);
                        $day = sprintf('%02d', $record['day']);
                        $overtime = sprintf('%.1f', $record['overtime'] / 3600);
                        $overtimeStart = substr($record['overtime_start'], 0, strrpos($record['overtime_start'], ':'));
                        $endTime = substr($record['end_time'], 0, strrpos($record['end_time'], ':'));
                        ?>
                        <?php echo "{$record['year']}-{$month}-{$day}   {$overtimeStart}   {$endTime}   {$overtime}\n"; ?>
                        <?php endforeach; ?>
                    </textarea>
    </form>
    <h2>餐补申请模板：</h2>

    <form>
                    <textarea class="form-control" rows="10">
                        <?php
                        foreach ($mealAllowanceRecords as $record) :
                        $month = sprintf('%02d', $record['month']);
                        $day = sprintf('%02d', $record['day']);
                        $overtimeStart = substr($record['overtime_start'], 0, strrpos($record['overtime_start'], ':'));
                        $endTime = substr($record['end_time'], 0, strrpos($record['end_time'], ':'));
                        ?>
                        <?php echo "{$user->name}  {$user->job_num}  {$record['year']}-{$month}-{$day}   {$overtimeStart} 至 {$endTime}\n"; ?>
                        <?php endforeach; ?>
                    </textarea>
    </form>
    <?php endif; ?>
</div>
@stop

@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#select-month').change(function () {
                var url = '<?=url('/record');?>';
                var year = $('#select-year').val();
                var month = $(this).val();
                window.location.href = url + '/' + year + '/' + month;
            });
        });
    </script>
@stop
