
//This comment is related with the first function

fn1('arg1', 'arg2', 3);
i.fn2($var);
a.b.c.fn3(fn4('arg4'), 'arg5', fn5(6, 7.5));
fn6(['arr']);
fn7(CONSTANT_1);
// fn_8();
/* ALLOW: This is a comment to fn9 */
fn9(ARG_8);

/* Comment to fn10 */ fn10({});
(function () {
    //Related comment 1
    fn11(/* ALLOW: Related comment 2 */ `arg9`, 'arg10' /* No related comment 3 */, `ignored dynamic ${value}`);
    
    /*
    Related comment 
    number one
    */
   fn12(
       /* Related comment 2 */
       'arg11',
       /* ALLOW: Related comment 3 */
       'arg12'
       /* No Related comment 4 */
    );
})
fn13(fn14(fn15('foo')));

// https://github.com/php-gettext/JS-Scanner/issues/3
var REACT_ELEMENT_TYPE = typeof Symbol === 'function' && Symbol['for'] && Symbol['for']('react.element') || 0xeac7;
