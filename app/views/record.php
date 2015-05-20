<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="UTF-8">
        <title>首页</title>
        <link rel="stylesheet" type="text/css" href="<?= PUB_URL ?>/css/bootstrap.min.css">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body style="padding-top: 50px;">

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand">考勤 Helper</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="<?= BASE_URL . '/record' ?>">首页</a></li>
                        <li><a href="<?= BASE_URL . '/about' ?>">关于</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if (empty($_COOKIE['job_num'])) : ?>
                            <li><a href="<?= BASE_URL . '/login' ?>">登录</a></li>
                        <?php else: ?>
                            <form class="navbar-form navbar-left" method="POST" action="<?= BASE_URL . '/logout' ?>">
                                <input type="submit" class="btn btn-default" value="退出" />
                            </form>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
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
        <script type="text/javascript" src="<?= PUB_URL ?>/js/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="<?= PUB_URL ?>/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#select-month').change(function () {
                    var url = '<?= BASE_URL ?>';
                    var year = $('#select-year').val();
                    var month = $(this).val();
                    window.location.href = url + '/record/' + year + '/' + month;
                });
            });
        </script>
    </body>
</html>