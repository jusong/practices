/*****************************************************************
 * 文件名称：FileDemo.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-01-03 14:19
 * 描    述：
 *****************************************************************/

import java.io.*;

public class FileDemo {

	public static void test1() {

		String dirname = "/tmp/";
		File f1 = new File(dirname);
		if (f1.isDirectory()) {
			System.out.println("Directory of " + dirname);
			String[] s = f1.list();
			for (int i = 0; i < s.length; i++) {
				File f = new File(dirname + "/" + s[i]);
				if (f.isDirectory()) {
					System.out.println(s[i] + " is Directory");
				} else {
					System.out.println(s[i] + " is a file");
				}
			}
		} else {
			System.out.println(dirname + " is not a Directory");
		}
	}

	public static void test2(String dirName, int level) {

		if (dirName.length() <= 0) {
			System.out.println("请指定有效的目录!");
			return;
		}
		if (level <= 0) {
			level = 0;
		}

		File dirFile = new File(dirName);
		if (level > 0) {
			System.out.print("│");
		}
		for (int i = 0; i < level; i++) {
			System.out.print("─");
		}
		if (level > 0) {
			System.out.print(" ");
		}
		System.out.println(dirFile.getName());

		if (dirFile.isDirectory()) {

			File[] files = dirFile.listFiles();
			if (null != files) {
				for (File file : files) {

					if (file.isFile()) {
						for (int i = 0; i < level; i++) {
							if (i % 5 == 0) {
								System.out.print("│");
							} else {
								System.out.print("  ");
							}
						}
						System.out.print("│");
						for (int i = 0; i < 5; i++) {
							System.out.print("─");
						}
						System.out.println(" " + file.getName());
					}
				}
				for (File file : files) {

					if (file.isDirectory()) {
						test2(file.getPath(), level + 5);
					}
				}
			}
		} else {
			for (int i = 0; i < level; i++) {
				if (i % 5 == 0) {
					System.out.print("│");
				} else {
					System.out.print("  ");
				}
			}
			System.out.print("│");
			for (int i = 0; i < 5; i++) {
				System.out.print("─");
			}
			System.out.println(" " + dirFile.getName());
		}
	}

	public static void test3() throws IOException {

		File file = new File("javadev/src/file");
		System.out.println(file.getName());
		System.out.println(file.getPath());
		System.out.println(file.getParent());
		System.out.println(file.getAbsoluteFile());
		System.out.println(file.getAbsolutePath());
		System.out.println(file.getCanonicalFile());
		System.out.println(file.getCanonicalPath());
		System.out.println(file.length());
		File[] roots = File.listRoots();
		for (File root : roots) {
			System.out.println("root: " + root.toString());
		}

		//if (!file.createNewFile()) {
		//	System.out.println("createNewFile false.");
		//}
//		if (file.renameTo(new File("/tmp/javadev/src/file1"))) {
//			System.out.println(file.getName());
//			System.out.println(file.getPath());
//			System.out.println(file.getParent());
//			System.out.println(file.getAbsoluteFile());
//			System.out.println(file.getAbsolutePath());
//			System.out.println(file.getCanonicalFile());
//			System.out.println(file.getCanonicalPath());
//		} else {
//			System.out.println("rename failed.");
//		}

		System.out.println(file.toString());
		System.out.println(file.getPath());
		file.setExecutable(true, false);
	}

	public static void test4(String fileName) {

		File file = new File(fileName);
		if (!file.exists()) {
			System.out.println("文件不存在：" + fileName);
		}

		if (file.isDirectory()) {
			File[] fileList = file.listFiles();
			for (File _file : fileList) {
				if (_file.isDirectory()) {
					test4(fileName + "/" + _file.getName());
				} else {
					_file.delete();
				}
			}
		}
		file.delete();
	}

	public static void main(String[] args) throws Exception {
		//System.out.println(args);
		//test2(args[0], 0);
		test4("/tmp/javadev");
	}
}
