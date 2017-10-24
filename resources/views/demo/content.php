<div class="row">
  <div class="col">
    <div class="jumbotron">
      <h1 class="display-3">Swoft framework</h1>
      <p class="lead">
        swoft 是基于swoole协程2.x的高性能PHP微服务框架，内置http,rpc 服务器。框架全协程实现，性能优于传统的php-fpm模式。
      </p>
      <hr class="my-4">
      <p>更多信息请访问 github 或者 官方文档.</p>
      <p class="lead">
        <a class="btn btn-primary btn-lg" href="<?=$repo?>" role="button" target="_blank">Github</a>
        <a class="btn btn-info btn-lg" href="<?=$doc?>" role="button" target="_blank">Document</a>
      </p>
    </div>
    <div>
      <h2>使用布局文件</h2>
      <pre>
view file: <?= __FILE__ ?>

layout file: <?= $layoutFile ?>

view method: <?= $method ?>


<strong>使用布局文件， 方式有两种：</strong>

1. 在配置中 配置默认的布局文件，那这里即使不设置 layout， 也会使用默认的
2. 如这里一样，手动设置一个布局文件。它的优先级更高（即使有默认的布局文件，也会使用当前传入的替代。）
    </pre>
    </div>
  </div>
</div>