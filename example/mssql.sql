-- ----------------------------
-- Table structure for [dbo].[EXAMPLE_TBL]
-- ----------------------------
DROP TABLE [dbo].[EXAMPLE_TBL]
GO
CREATE TABLE [dbo].[EXAMPLE_TBL] (
[id] int NOT NULL IDENTITY(1,1) ,
[name] varchar(50) NULL ,
[email] varchar(50) NULL 
)


GO
DBCC CHECKIDENT(N'[dbo].[EXAMPLE_TBL]', RESEED, 0)
GO

-- ----------------------------
-- Records of EXAMPLE_TBL
-- ----------------------------
SET IDENTITY_INSERT [dbo].[EXAMPLE_TBL] ON
GO
SET IDENTITY_INSERT [dbo].[EXAMPLE_TBL] OFF
GO
