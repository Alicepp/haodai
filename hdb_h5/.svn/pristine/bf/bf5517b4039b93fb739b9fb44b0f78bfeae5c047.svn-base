// 设置命名空间
fis.config.set('namespace', 'common');
// npm install [-g] fis3-hook-commonjs

var sModPath = 'static/';
fis.hook('commonjs', {
    baseUrl: sModPath,
    extList: ['.js']
});

fis.match('widget/**/**.js', {
    isMod: true
})

// 插件 fis-spriter-csssprites
fis.config.set('modules.spriter', 'csssprites');

fis.config.set('settings.spriter.csssprites', {
    //图之间的边距 
    margin: 10
        //使用矩阵排列方式，默认为线性`linear` 
        // layout: 'matrix'
});

// 对 CSS 进行图片合并
fis.match('*.css', {
    // 给匹配到的文件分配属性 `useSprite`
    useSprite: true
});

fis.match('*.png', {
    // fis-optimizer-png-compressor 插件进行压缩，已内置
    optimizer: fis.plugin('png-compressor')
});

fis.match('static/css/*.css', {
    // fis-optimizer-uglify-js 插件进行压缩，已内置
    optimizer: fis.plugin('clean-css'),
    release: '/pkg/css/*'
})

fis.match('*.{js,css,png,gif}', {
  useHash: true
});

// bank_logo的图片不改名
// fis.match('**/bank_logo/*.png', {
//   useHash: false
// });

fis.match('::package', {
    packager: fis.plugin('map', {
        useTrack:false,
        'pkg/l.js': [
          '/static/lib/**.js',
          '!/static/lib/echarts.js',
          '!/static/lib/swiper.js'
        ],
        'pkg/c.js': [
          '/static/widget/common/*'
        ],
        'pkg/c.css': [
          '/static/css/common/common.css',
          '/static/css/common/swiper-3.3.1.min.css'
        ]
    }),
    // npm install [-g] fis3-postpackager-loader
    // 分析 __RESOURCE_MAP__ 结构，来解决资源加载问题
    postpackager: fis.plugin('loader', {
        resourceType: 'commonJs',
        // allInOne:true,
        allInOne: {
          js: function (file) {
            return "pkg/js/" + file.filename + "_aio.js";
          },
          css: function (file) {
            return "pkg/css/" + file.filename + "_aio.css";
          }
        },
        useInlineMap: false, // 资源映射表内嵌
        sourceMap: false, //是否生成依赖map文件
    })
})

fis.match('/static/(widget/**/init.js)', {
    release: '/pkg/js/$2'
});

fis.match('/static/(images/**/*)', {
    release: '/pkg/$1'
});

// 压缩html、css、js
fis.media('mini')
    .match('widget/**/*.js', {
      optimizer: fis.plugin('uglify-js')
    })
    .match('static/**/*.css', {
      optimizer: fis.plugin('clean-css')
    })
    .match('**.htm', {
        optimizer: fis.plugin('html-minifier')
    })
// 添加域名
fis.media('domain')
    .match('widget/**/*.js', {
      optimizer: fis.plugin('uglify-js')
    })
    .match('static/**/*.css', {
      optimizer: fis.plugin('clean-css')
    })
    .match('**.htm', {
        optimizer: fis.plugin('html-minifier')
    })
    .match('*', {
      domain: 'http://static.haodaibao.com/h5'
    })