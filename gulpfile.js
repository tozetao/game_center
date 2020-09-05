const { src, dest, watch, series } = require('gulp');

function clean(ob) {
  console.log('clean');
  ob();
}

function jsTask(done) {
  return src('./resources/assets/js/**/**.js')
    .pipe(dest('./public'));
}

function cssTack() {

}

function htmlTask() {

}

function watches() {
  watch('./resources/assets/js/**/**.js', series(clean, jsTask))
}

exports.default = function(done) {
  console.log('hello world.');
  done();
};

//cnpm install -g glup
//cnpm install --save-dev browser-sync