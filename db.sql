create database courtship;

create table categories(
    id int not null primary key auto_increment,
    name varchar(15) not null,
    pid int default 0 comment '上级id'
)engine=innodb charset=utf8mb4;


-- 商品类目定义的规格属性表
create table specification_attrs(
    spec_id     int auto_increment   comment '规格id，自增id或自定义',
    category_id int                  comment '类目id',
    attr_name   varchar(10)   comment '属性名',
    attr_values varchar(200)  comment '属性值列表',
    is_mulit    tinyint       comment '是否有多个销售属性值，0表示当选，1表示多选。',
    is_custom   tinyint       comment '是否允许自定义销售属性，即可以修改原本的销售属性值。',
--     has_icon    tinyint       comment '是否可以有图片描述',
--     has_color   tinyint       comment '是否可以有颜色',
    primary key(spec_id)
)engine=innodb charset=utf8mb4;


-- 商品表
create table goods(
    id     int not null auto_increment      comment '商品id',
    title  varchar(60) not null             comment '标题',
    desc   varchar(200) not null            comment '描述',
    image  varchar(200) not null            comment '商品主图',
    price  int unsigned not null            comment '原价',
    discount_price int unsigned not null    comment '现价',
    is_exchange tinyint not null            comment '是否可积分兑换, ',
    points int not null                     comment '商品对应的积分',
    on_sale    tinyint not null             comment '上架状态',
    category_id int not null                comment '类目id',
    stock int not null                      comment '库存',
    created_at int                          comment '创建时间',
    goods_number varchar(20)                comment '商品编号',
    postage_id tinyint default 0            comment '邮费模板id'
) engine=innodb charset=utf8mb4;


-- 商品规格属性
-- 1, '红色', 2001, 1001(销售规格：尺码)
create table goods_specifications(
    attr_id int unsigned primary key auto_increment comment '商品规格属性id',
    attr_val varchar(10) comment '商品规格属性值',
    goods_id int not null comment '商品id',
    spec_id int not null comment '销售规格id',
    primary key(attr_id),
    key(goods_id)
);

-- 商品关联主图
create table goods_images(
    id int not null auto_increment  comment 'id',
    goods_id    int not null        comment '商品关联id',
    path        int not null        comment '图片存储路径',
    primary key(id),
    key(goods_id)
);

-- 用户表
create table users(
  uid    int not null auto_increment comment '用户id',
  nickname varchar(50) not null      comment '昵称',
  openid   varchar(50) not null      comment 'openid',
  icon     varchar(300) not null     comment '用户头像',
  last_online_time int not null      comment '最后一次在线时间',
  created_at int not null            comment '创建时间',
  logged_at int not null             comment '登陆时间',
  points   int not null              comment '用户积分',
  sex tinyint default 0              comment '0未设置，1男2女',
  revive_times int default 0         comment '复活次数',
  key(openid),
  primary key(uid)
)engine=innodb charset=utf8mb4;


-- 用户收货地址表
create table user_addr(
  id  int not null auto_increment   comment '地址id',
  uid int not null                  comment '用户id',
  province_id int not null          comment '省份id',
  province varchar(15)              comment '省份',
  city_id     int not null          comment '市id',
  city     varchar(15)              comment '市',
  area_id     int not null          comment '区id',
  area     varchar(15)              comment '区域',
  info     varchar(150)             comment '更详细的信息',
  addressee varchar(12) not null    comment '收件人',
  phone    varchar(12) not null     comment '手机号'
  primary key(id),
  key(uid)
)engine=innodb charset=utf8mb4;

-- 商品订单
create table orders(
  order_id int not null auto_increment   comment '订单id',
  uid int not null                  comment '用户id',
  order_number varchar(30) not null comment '订单编号',
  goods_id int not null             comment '商品id',
  title varchar(60) not null        comment '商品标题',
  image varchar(200) not null       comment '商品图片',
  price int not null                comment '原价',
  discount_price int not null       comment '抵扣价',
  points int not null               comment '积分',
  payment_type tinyint not null     comment '支付类型，1是积分、2是RMB',
  category_id int not null          comment '商品分类id',
  quantity int not null             comment '购买数量',
  status tinyint not null           comment '订单状态，1是未支付，2是已支付，3是取消',
  paid_at int not null              comment '支付时间',
  created_at int not null           comment '创建时间',
  updated_at int not null           comment '更新时间',
  delivery_status tinyint not null  comment '发货状态，0未发货，1是已发货',
  express_name varchar(20) not null comment '快递公司名字',
  express_number varchar(30) not null comment '快递单号',
  primary key(order_id),
  key(order_number, goods_id),
  key(uid)
)engine=innodb charset=utf8mb4;

create table order_specifications(
  id int not null auto_increment    comment '自增id',
  order_id int not null             comment '订单id',
  spec_id int not null              comment '销售规格id',
  spec_name varchar(10) not null    comment '具体的销售规格',
  attr_id int not null              comment '销售属性id',
  attr_val varchar(10) not null     comment '销售属性值',
  primary key(id),
  key(order_id)
)engine=innodb charset=utf8mb4;

-- 订单地址
create table order_addrs(
  id int not null auto_increment    comment '自增id',
  order_id int not null             comment '订单id',
  province varchar(15)              comment '省份',
  city     varchar(15)              comment '市',
  area     varchar(15)              comment '区域',
  info     varchar(150)             comment '更详细的信息',
  addressee varchar(12)             comment '收件人',
  phone     varchar(12)             comment '电话',
  primary key(id),
  key(order_id)
)engine=innodb charset=utf8mb4;

create table areas(
  area_id int not null,
  name varchar(20) not null,
  type tinyint not null,
  pid int not null,
  primary key(area_id)
)engine=innodb charset=utf8mb4;

-- archives与base_setting是一对一关系；与icon_setting是一堆多关系；与checkpoint_setting关卡设置是一对多关系。

create table archives(
  id int primary key auto_increment,
  master_id int not NULL            comment '存档人的id',
  created_at int not null           comment '创建时间',
  key(master_id)
)engine=innodb charset=utf8mb4;

-- 基础设置
create table base_setting (
  id int primary key auto_increment,
  archive_id int not null           comment '存档id',
  master_id int not null            comment '设置者id',
  my_name varchar(30) default ''    comment '我的昵称',
  other_name varchar(30) default '' comment '对方昵称',
  hair int default 0                comment '发型道具的id',
  clothes int default 0             comment '衣服道具的id',
  sex tinyint default 0             comment '性别',
  key(archive_id)
) engine=innodb charset=utf8mb4;


-- 表情设置
create table icon_setting(
  id int primary key auto_increment,
  archive_id int not null           comment '存档id',
  master_id int not null            comment '设置者id',
  icon varchar(200) not null        comment '头像路径',
  `type` tinyint not null           comment '1睁眼、2闭眼、3口型啊、4口型哦，5大笑，6微笑。',
  key(archive_id)
)engine=innodb charset=utf8mb4;


-- 关卡设置
create table checkpoint_setting(
  id int primary key auto_increment,
  archive_id int not null           comment '存档id',
  master_id int not null              comment '设置者id',
  success_msg varchar(200) not null   comment '成功消息',
  fail_msg varchar(200) not null      comment '失败消息',
  image varchar(200) not null         comment '图片路径',
  `number`  tinyint  not NULL         comment '关卡编号',
  red_envelope_no varchar(30) default '' comment '红包编号',
  key(archive_id)
)engine=innodb charset=utf8mb4;

-- 我的下线
create table my_subordinate(
  id int not null primary key auto_increment,
  master_id int not null          comment '主id',
  slave_id int not null           comment '下线id'
)engine=innodb charset=utf8mb4;

-- 道具表
create table props(
  prop_id int not null primary key comment '道具id，自定义',
  `type`    int not null      comment '道具类型，1是头发，2是衣服',
  price     int not null      comment '价格，单位分',
  tag       tinyint not null  comment '标签，1vip、2是限时免费',
  prop_name varchar(50) not null  comment '道具名字',
  remarks   varchar(50) not null  comment '备注',
  image     varchar(200) not null comment '道具图片路径'
)engine=innodb charset=utf8mb4;

-- 道具订单
create table prop_order(
    id int primary key auto_increment,
    number varchar(30) not null comment '订单编号',
    user_id int not null,
    status tinyint not null     comment '订单状态，与order表订单状态一致',
    payment_type tinyint not null comment '支付方式',
    created_at int not null     comment '创建时间',
    pay_at int not null         comment '支付时间',
    index(number)
)engine=innodb charset=utf8mb4;

-- 道具订单主题，包含玩家购买的道具列表
create table prop_order_body(
    id int primary key auto_increment,
    prop_id int not null,
    prop_name varchar(30) not null,
    prop_order_id int not null,
    price int not null comment '道具价格，单位分',
    image varchar(200) not null,
    index (prop_order_id)
)engine=innodb charset=utf8mb4;

-- 我的道具
create table my_props(
  id int primary key auto_increment,
  uid int not null      comment '玩家id',
  prop_id int not null  comment '道具id',
  key(uid)
)engine=innodb charset=utf8mb4;


create table express(
  express_id int primary key auto_increment comment '自增id',
  express_name varchar(30) not null         comment '快递公司名字'
)engine=innodb charset=utf8mb4;

-- 申请复活表
create table apply_resurrection(
  id int primary key auto_increment,
  master_id int not NULL      comment '制作者id',
  slave_id int not null       comment '玩家id',
  status tinyint not null     comment '状态，1是玩家申请复活，2是玩家取消复活，3是制作者确认复活，4是制作者取消复活'
)engine=innodb charset=utf8mb4;

-- 红包订单日志
create table red_envelope_log(
    id int primary key auto_increment,
    number varchar(30) not null comment '红包编号',
    val int not null            comment '红包数额，单位分',
    real_val int not null       comment '真实价格',
    status tinyint not null     comment '订单状态，1是未支付，2是已支付，3是已发送',
    user_id int not null        comment '用户id',
    created_at int not null,
    sent_at int not null        comment '发送时间',
    pay_at int not null         comment '支付时间',
    index(number)
)engine=innodb charset=utf8mb4;