/*
 Navicat Premium Data Transfer

 Source Server         : locahost
 Source Server Type    : MySQL
 Source Server Version : 50540
 Source Host           : 127.0.0.1:3306
 Source Schema         : admin

 Target Server Type    : MySQL
 Target Server Version : 50540
 File Encoding         : 65001

 Date: 26/06/2019 17:46:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cy_catalog
-- ----------------------------
DROP TABLE IF EXISTS `cy_catalog`;
CREATE TABLE `cy_catalog`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pid` int(4) NULL DEFAULT 0 COMMENT '父级id 0-一级目录',
  `createTime` int(11) NULL DEFAULT NULL,
  `rule` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '规则 所在控制器、方法',
  `rank` int(4) NULL DEFAULT 0 COMMENT '排序 大到小',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 15 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '目录结构表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cy_catalog
-- ----------------------------
INSERT INTO `cy_catalog` VALUES (1, '权限功能', 0, 1561365326, 'rule', 5);
INSERT INTO `cy_catalog` VALUES (2, '角色', 1, 1561365326, 'role', 7);
INSERT INTO `cy_catalog` VALUES (3, '目录功能', 1, 1561365326, 'catalog', 4);
INSERT INTO `cy_catalog` VALUES (4, '运营数据', 0, 2019, 'operate', 4);
INSERT INTO `cy_catalog` VALUES (5, '玩家相关', 0, 1561445961, 'plyerplayer', 3);
INSERT INTO `cy_catalog` VALUES (6, 'GM工具', 0, 1561446136, 'gm', 3);
INSERT INTO `cy_catalog` VALUES (13, '礼包管理', 0, 1561452444, 'gift', 2);

-- ----------------------------
-- Table structure for cy_role
-- ----------------------------
DROP TABLE IF EXISTS `cy_role`;
CREATE TABLE `cy_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `createTime` int(11) NULL DEFAULT NULL,
  `createUser` int(5) NULL DEFAULT NULL COMMENT '创建人',
  `createPower` tinyint(1) NULL DEFAULT 0 COMMENT '编辑角色权限 1-有 0-无',
  `realPass` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cy_role
-- ----------------------------
INSERT INTO `cy_role` VALUES (1, 'admin', '25b6e0de03803595e069456b4b37fd8c', 1561365326, 0, 1, 'cyAdmin');

-- ----------------------------
-- Table structure for cy_role_catalog
-- ----------------------------
DROP TABLE IF EXISTS `cy_role_catalog`;
CREATE TABLE `cy_role_catalog`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` int(11) NOT NULL COMMENT '角色id',
  `cataId` int(11) NOT NULL COMMENT '目录id',
  `createTime` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 42 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色权限表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of cy_role_catalog
-- ----------------------------
INSERT INTO `cy_role_catalog` VALUES (1, 1, 1, 1561365326);
INSERT INTO `cy_role_catalog` VALUES (2, 1, 2, 1561365326);
INSERT INTO `cy_role_catalog` VALUES (3, 1, 3, 1561365326);
INSERT INTO `cy_role_catalog` VALUES (5, 1, 13, 1561452444);
INSERT INTO `cy_role_catalog` VALUES (6, 1, 4, 1561452444);
INSERT INTO `cy_role_catalog` VALUES (7, 1, 5, 1561452444);
INSERT INTO `cy_role_catalog` VALUES (8, 1, 6, 1561452444);

SET FOREIGN_KEY_CHECKS = 1;
