/*
 Navicat Premium Data Transfer

 Source Server         : vm_centos7
 Source Server Type    : MySQL
 Source Server Version : 80017
 Source Host           : 192.168.18.173:3306
 Source Schema         : laravel6

 Target Server Type    : MySQL
 Target Server Version : 80017
 File Encoding         : 65001

 Date: 03/03/2020 09:35:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for config_group
-- ----------------------------
DROP TABLE IF EXISTS `config_group`;
CREATE TABLE `config_group`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `sort` tinyint(4) NOT NULL DEFAULT 10 COMMENT '排序',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '配置组表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of config_group
-- ----------------------------
INSERT INTO `config_group` VALUES (1, '系统配置', 1, '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `config_group` VALUES (2, '微信公众号配置', 5, '2020-01-19 15:55:34', '2020-01-19 15:55:34');

-- ----------------------------
-- Table structure for configuration
-- ----------------------------
DROP TABLE IF EXISTS `configuration`;
CREATE TABLE `configuration`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT 0 COMMENT '组ID',
  `label` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '配置项名称',
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '配置项字段',
  `val` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '配置项值',
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'input' COMMENT '配置项类型，input输入框，radio单选，select下拉,image单图片',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '配置项类型的内容',
  `tips` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '输入提示',
  `sort` tinyint(4) NOT NULL DEFAULT 10 COMMENT '排序',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '配置项表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of configuration
-- ----------------------------
INSERT INTO `configuration` VALUES (1, 1, '后台标题', 'site_title', '后台管理系统', 'input', '', '', 10, '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `configuration` VALUES (2, 1, '登录日志', 'login_log', '1', 'radio', '0:关闭|1:开启', '开启后将记录后台登录日志', 10, '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `configuration` VALUES (3, 1, '删除登录日志', 'delete_login_log', '1', 'radio', '0:禁止|1:允许', '开启后将允许后台删除登录日志', 10, '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `configuration` VALUES (4, 2, 'AppID', 'wechat_app_id', 'your-app-id', 'input', '', '', 10, '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `configuration` VALUES (5, 2, 'AppSecret', 'wechat_secret', 'your-app-secret', 'input', '', '', 10, '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `configuration` VALUES (6, 2, 'Token', 'wechat_token', 'your-token', 'input', '', '', 10, '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `configuration` VALUES (7, 2, 'EncodingAESKey', 'wechat_aes_key', NULL, 'input', '', '兼容与安全模式下请一定要填写！！！', 10, '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `configuration` VALUES (16, 2, '图片测试', 'img_test', '/uploads/local/2020-03-02_1583134535_5e5cb7478777d.png', 'image', NULL, '请上传图片', 10, '2020-03-02 11:40:41', '2020-03-02 11:40:41');

-- ----------------------------
-- Table structure for login_log
-- ----------------------------
DROP TABLE IF EXISTS `login_log`;
CREATE TABLE `login_log`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录用户名',
  `ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录IP地址',
  `method` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '请求方式',
  `user_agent` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'UserAgent',
  `remark` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of login_log
-- ----------------------------
INSERT INTO `login_log` VALUES (10, 'admin', '127.0.0.1', 'POST', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36', '登录成功', '2020-03-03 08:44:23', '2020-03-03 08:44:23');

-- ----------------------------
-- Table structure for model_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions`  (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_permissions_model_id_model_type_index`(`model_id`, `model_type`) USING BTREE,
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户-权限中间表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_permissions
-- ----------------------------
INSERT INTO `model_has_permissions` VALUES (1, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (2, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (3, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (4, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (5, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (6, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (7, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (8, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (9, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (10, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (11, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (12, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (13, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (14, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (15, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (16, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (17, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (18, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (19, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (20, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (21, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (22, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (23, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (24, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (25, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` VALUES (26, 'App\\Models\\User', 1);

-- ----------------------------
-- Table structure for model_has_roles
-- ----------------------------
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles`  (
  `role_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_roles_model_id_model_type_index`(`model_id`, `model_type`) USING BTREE,
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户-角色中间表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_roles
-- ----------------------------
INSERT INTO `model_has_roles` VALUES (1, 'App\\Models\\User', 1);

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `route` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '路由名称',
  `icon` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '图标class',
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `type` int(11) NOT NULL DEFAULT 1 COMMENT '类型：1按钮，2菜单',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 42 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '权限表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES (1, 'system', 'web', '系统管理', NULL, 'layui-icon-set', 0, 0, 1, '2020-01-19 15:55:33', '2020-02-25 16:41:23');
INSERT INTO `permissions` VALUES (2, 'system.user', 'web', '用户管理', 'admin.user', '1', 1, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (3, 'system.user.create', 'web', '添加用户', 'admin.user.create', '1', 2, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (4, 'system.user.edit', 'web', '编辑用户', 'admin.user.edit', '1', 2, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (5, 'system.user.destroy', 'web', '删除用户', 'admin.user.destroy', '1', 2, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (6, 'system.user.role', 'web', '分配角色', 'admin.user.role', '1', 2, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (7, 'system.user.permission', 'web', '分配权限', 'admin.user.permission', '1', 2, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (8, 'system.role', 'web', '角色管理', 'admin.role', '1', 1, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (9, 'system.role.create', 'web', '添加角色', 'admin.role.create', '1', 8, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (10, 'system.role.edit', 'web', '编辑角色', 'admin.role.edit', '1', 8, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (11, 'system.role.destroy', 'web', '删除角色', 'admin.role.destroy', '1', 8, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (12, 'system.role.permission', 'web', '分配权限', 'admin.role.permission', '1', 8, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (13, 'system.permission', 'web', '权限管理', 'admin.permission', '1', 1, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (14, 'system.permission.create', 'web', '添加权限', 'admin.permission.create', '1', 13, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (15, 'system.permission.edit', 'web', '编辑权限', 'admin.permission.edit', '1', 13, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (16, 'system.permission.destroy', 'web', '删除权限', 'admin.permission.destroy', '1', 13, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (17, 'system.config_group', 'web', '配置组', 'admin.config_group', '1', 1, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (18, 'system.config_group.create', 'web', '添加组', 'admin.config_group.create', '1', 17, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (19, 'system.config_group.edit', 'web', '编辑组', 'admin.config_group.edit', '1', 17, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (20, 'system.config_group.destroy', 'web', '删除组', 'admin.config_group.destroy', '1', 17, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (21, 'system.configuration', 'web', '配置项', 'admin.configuration', '1', 1, 0, 2, '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `permissions` VALUES (22, 'system.configuration.create', 'web', '添加组', 'admin.configuration.create', '1', 21, 0, 2, '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `permissions` VALUES (23, 'system.configuration.edit', 'web', '编辑组', 'admin.configuration.edit', '1', 21, 0, 2, '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `permissions` VALUES (24, 'system.configuration.destroy', 'web', '删除组', 'admin.configuration.destroy', '1', 21, 0, 2, '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `permissions` VALUES (25, 'system.login_log', 'web', '登录日志', 'admin.login_log', '1', 1, 0, 2, '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `permissions` VALUES (26, 'system.login_log.destroy', 'web', '删除', 'admin.login_log.destroy', '1', 25, 0, 2, '2020-01-19 15:55:34', '2020-01-19 15:55:34');

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions`  (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`) USING BTREE,
  INDEX `role_has_permissions_role_id_foreign`(`role_id`) USING BTREE,
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '角色-权限中间表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------
INSERT INTO `role_has_permissions` VALUES (1, 1);
INSERT INTO `role_has_permissions` VALUES (2, 1);
INSERT INTO `role_has_permissions` VALUES (3, 1);
INSERT INTO `role_has_permissions` VALUES (4, 1);
INSERT INTO `role_has_permissions` VALUES (5, 1);
INSERT INTO `role_has_permissions` VALUES (6, 1);
INSERT INTO `role_has_permissions` VALUES (7, 1);
INSERT INTO `role_has_permissions` VALUES (8, 1);
INSERT INTO `role_has_permissions` VALUES (9, 1);
INSERT INTO `role_has_permissions` VALUES (10, 1);
INSERT INTO `role_has_permissions` VALUES (11, 1);
INSERT INTO `role_has_permissions` VALUES (12, 1);
INSERT INTO `role_has_permissions` VALUES (13, 1);
INSERT INTO `role_has_permissions` VALUES (14, 1);
INSERT INTO `role_has_permissions` VALUES (15, 1);
INSERT INTO `role_has_permissions` VALUES (16, 1);
INSERT INTO `role_has_permissions` VALUES (17, 1);
INSERT INTO `role_has_permissions` VALUES (18, 1);
INSERT INTO `role_has_permissions` VALUES (19, 1);
INSERT INTO `role_has_permissions` VALUES (20, 1);
INSERT INTO `role_has_permissions` VALUES (21, 1);
INSERT INTO `role_has_permissions` VALUES (22, 1);
INSERT INTO `role_has_permissions` VALUES (23, 1);
INSERT INTO `role_has_permissions` VALUES (24, 1);
INSERT INTO `role_has_permissions` VALUES (25, 1);
INSERT INTO `role_has_permissions` VALUES (26, 1);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'root', 'web', '超级管理员', '2020-01-19 15:55:33', '2020-01-19 15:55:33');
INSERT INTO `roles` VALUES (2, 'business', 'web', '商务', '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `roles` VALUES (3, 'assessor', 'web', '审核员', '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `roles` VALUES (4, 'channel', 'web', '渠道专员', '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `roles` VALUES (5, 'editor', 'web', '编辑人员', '2020-01-19 15:55:34', '2020-01-19 15:55:34');
INSERT INTO `roles` VALUES (6, 'admin', 'web', '管理员', '2020-01-19 15:55:34', '2020-01-19 15:55:34');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号码',
  `nickname` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '昵称',
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '邮箱',
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `api_token` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_username_unique`(`username`) USING BTREE,
  UNIQUE INDEX `users_phone_unique`(`phone`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE,
  UNIQUE INDEX `users_api_token_unique`(`api_token`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin', '17355105312', '超级管理员', 'wuruiwm@qq.com', '$2y$10$eOvr3LmME3nFGBObpHFaSuf7XfzICR.6ld/jDydwrBd3rCgE01Xyq', NULL, 'b7806eb968f4cb230602dd623783b2830f965e7bda01acea093d4efabf33fb1f', '2020-01-19 15:55:33', '2020-02-25 11:46:47');

SET FOREIGN_KEY_CHECKS = 1;
