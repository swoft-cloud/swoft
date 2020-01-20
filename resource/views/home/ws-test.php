<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>WebSocket Testing - Swoft 2.0</title>
  <link href="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.bootcss.com/icono/1.3.0/icono.min.css" rel="stylesheet">
  <style>
    /** Large devices (desktops, less than 1200px) */
    @media (min-width: 1400px) {
      .container {
        max-width: 1340px;
      }
    }

    #message-box {
      min-height: 410px;
      max-height: 600px;
      overflow: auto;
    }

    .text-sm {
      font-size: small;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">Swoft</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="/"><i data-feather="home"></i> Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" target="_blank" href="https://github.com/swoft-cloud/swoft"><i data-feather="github"></i>
            Github</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" target="_blank" href="https://swoft.org/docs"><i data-feather="book"></i> 中文文档</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" target="_blank" href="http://swoft.io/docs"><i data-feather="book"></i> English Docs</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="my-1">
</div>
<div class="container">
  <div class="row">
    <div class="col" id="page-head">
      <h1 class="py-2 my-4">WebSocket Testing - <small class="font-italic text-muted">Swoft framework 2.0</small></h1>
      <hr>
      <div class="alert alert-warning alert-dismissible fade" role="alert">
        <strong>Warning!</strong> <span class="alert-txt">Hi</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-4">
      <div class="card border-info config-box">
        <h5 class="card-header"><i data-feather="settings"></i> Configuration</h5>
        <div class="card-body">
          <div class="card-title">WebSocket Server Address</div>
          <div class="input-group mb-3">
            <input type="text" id="ws-addr"
                   class="form-control"
                   placeholder="websocket server address"
                   aria-label="websocket server address"
                   aria-describedby="btn-conn"
                   value="ws://127.0.0.1:18308/echo"
                   list="addr-list">
            <div class="input-group-append">
              <button class="btn btn-outline-primary" type="button" id="btn-conn">
                <i data-feather="link"></i>
              </button>
            </div>
            <datalist id="addr-list">
              <option value="ws://127.0.0.1:18308/echo">
              <option value="ws://127.0.0.1:18308/chat">
              <option value="ws://127.0.0.1:18308/test">
              <option value="wss://echo.websocket.org/">
            </datalist>
          </div>
          <div>
            <p class="bg-light text-info rounded-lg">Please note that the server address, port, path, etc. are correct</p>
          </div>
          <form action="#">
            <div class="form-group">
              <label for="input-message">Input Message</label>
              <textarea class="form-control" id="input-message" rows="8" minlength="1"></textarea>
            </div>
            <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" id="cfg-clr-after-send">
              <label class="form-check-label" for="cfg-clr-after-send">Clear input after send</label>
            </div>
          </form>
        </div>
        <div class="card-footer bg-transparent">
          <button class="btn btn-sm btn-outline-info float-right" id="btn-sending"><i data-feather="send"></i> Send Message</button>
        </div>
      </div>
    </div>

    <div class="col-8">
      <div class="card">
        <h5 class="card-header text-white bg-primary"><i data-feather="message-square"></i> Messages</h5>
        <div class="card-body">
          <p class="card-text">websocket message box</p>
          <div id="message-box" class="bg-light p-2">
            <div class="message-item">
              <div class="text-right py-1">
                <span class="badge badge-secondary">You</span> at <span class="font-italic text-muted text-sm">2020-1-20 10:54:40</span>
              </div>
              <div class="clearfix">
                <div class="float-right badge badge-success rounded-pill p-2">
                  <span class="user-message">User send message example</span>
                </div>
              </div>
            </div>
            <div class="message-item">
              <div class="py-1">
                <span class="badge badge-secondary">Server</span> at <span class="font-italic text-muted text-sm">2020-1-20 10:54:40</span>
              </div>
              <div class="clearfix">
                <div class="float-left badge badge-primary rounded-pill p-2">
                  <span class="server-message">Server reply message example</span>
                </div>
              </div>
            </div>
          </div><!-- .message-box -->
        </div>
        <div class="card-footer bg-transparent">
          <div class="clearfix">
            <a href="javascript:void(0);" class="btn btn-sm btn-outline-secondary float-right" id="btn-clr-message">
              <i data-feather="trash"></i>
              Clear Messages
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="py-3">
  <hr>
</div>
<div class="footer mt-5 py-2">
  <ul class="nav justify-content-center my-3">
    <li class="nav-item">
      <a class="nav-link" target="_blank" href="https://github.com/swoft-cloud/swoft">Github</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" target="_blank" href="https://gitee.com/swoft/swoft">Gitee</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" target="_blank" href="https://github.com/swoft-cloud/swoft/issues">Issues</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" target="_blank" href="http://swoft.io/docs">English Docs</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" target="_blank" href="https://swoft.org/docs">中文文档</a>
    </li>
  </ul>
</div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.slim.min.js"></script>
<script src="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.bootcss.com/feather-icons/4.24.1/feather.min.js"></script>
<script>
  // apply icons
  feather.replace()
  $('.toast').toast()

  // ws connection
  let ws

  const config = {
    heartbeat: false,
    connected: false,
  }

  const pgHead = $('#page-head')
  const btnConn = $('#btn-conn')
  const btnSend = $('#btn-sending')
  const btnClr = $('#btn-clr-message')
  const inAddr = $('#ws-addr')
  const inText = $('#input-message')
  const msgBox = $('#message-box')

  btnConn.on('click', function (e) {
    let addr = inAddr.val()
    if (!addr || addr.length < 5) {
      show_alert('please input ws server address')
      return
    }

    // disconnect
    if (config.connected) {
      config.connected = false
      btnConn.addClass('btn-outline-primary').removeClass('btn-success')

      ws.close()
      return
    }

    // do connecting
    connect(addr)
  })

  btnSend.on('click', function (e) {
    send_message(inText.val())

    if ($('#cfg-clr-after-send').is(":checked")) {
      inText.val('')
    }
  })

  btnClr.on('click', function () {
    msgBox.find('.message-item').remove()
  })

  function connect(addr) {
    let timer

    console.info('begin connect to', addr)
    ws = new WebSocket(addr)

    ws.onerror = function error(e) {
      console.warn('connect failed!', e)
      show_alert('connect failed! addr:' + addr)
    }

    ws.onopen = function open(ev) {
      console.info('connected', ev)
      show_alert('connect successful', 'success')

      config.connected = true

      btnConn.removeClass('btn-outline-primary').addClass('btn-success')

      if (!config.heartbeat) {
        return
      }

      // send Heartbeat
      timer = setTimeout(function () {
        app.sendMessage('@heartbeat', false)
      }, 20000)
    }

    ws.onmessage = function incoming(me) {
      console.log('received', me)
      show_message(me.data, 's')
    }

    ws.onclose = function close() {
      config.connected = false
      if (config.heartbeat) {
        clearTimeout(timer)
      }

      ws = null
      console.info('disconnected')
      show_alert('disconnect from remote server', 'info')
    }
  }

  function send_message(msg) {
    if (!ws || !config.connected) {
      show_alert('please connect server before send message!')
      return
    }

    if (!msg) {
      show_alert('send message cannot be empty!')
      return
    }

    show_message(msg)
    ws.send(msg)
  }

  function show_message(msg, type = 'u') {
    let role = 'Server'
    let pos = 'left'
    let bStyle = 'primary'
    let bgStyle = ''

    if (type === 'u') {
      role = 'You'
      pos = 'right'
      bStyle = 'success'
      bgStyle = 'bg-success'
    }

    let curTime = date_format(new Date(), 'yy-MM-dd hh:mm:ss')

    let item = `<div class="message-item">
    <div class="text-${pos} py-1">
      <span class="badge badge-secondary">${role}</span> at <span class="font-italic text-muted text-sm">${curTime}</span>
    </div>
    <div class="clearfix">
      <div class="float-${pos} badge badge-${bStyle} rounded-pill p-2 ${bgStyle}">
        <span class="text-monospace">${msg}</span>
      </div>
    </div>
</div>`

    msgBox.append(item)
  }

  function show_alert(msg, type = 'warning', clear = true) {
    let html = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
        <strong>Warning!</strong> <span class="alert-txt">${msg}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>`

//     let html = `<div class="toast" style="position: absolute; top: 0; right: 0;">
//     <div class="toast-header">
//       <img src="..." class="rounded mr-2" alt="...">
//       <strong class="mr-auto">Notice!</strong>
//       <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
//         <span aria-hidden="true">&times;</span>
//       </button>
//     </div>
//     <div class="toast-body">
//        <span class="alert-txt">${msg}</span>
//     </div>
// </div>`

    let prev = pgHead.find('div.alert')
    if (prev.length > 0) {
      prev.remove()
    }

    pgHead.append(html)

    // clear after 5s
    if (clear) {
      setTimeout(function () {
        pgHead.find('div.alert').removeClass('show')
      }, 5000)
    }
  }

  // usage: date_format(new Date())
  function date_format(date, pattern = 'yyyy-MM-dd') {
    function strPad(str, padLen = 2) {
      if (str.length < padLen) {
        str = '0' + str
      }

      return str
    }

    return pattern.replace(/([yMdhsm])(\1*)/g, function ($0) {
      switch ($0.charAt(0)) {
        case 'y':
          return strPad(date.getFullYear(), $0.length)
        case 'M':
          return strPad(date.getMonth() + 1, $0.length)
        case 'd':
          return strPad(date.getDate(), $0.length)
        case 'w':
          return date.getDay() + 1
        case 'h':
          return strPad(date.getHours(), $0.length)
        case 'm':
          return strPad(date.getMinutes(), $0.length)
        case 's':
          return strPad(date.getSeconds(), $0.length)
      }
    })
  }
</script>
</html>
