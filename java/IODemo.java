/*****************************************************************
 * 文件名称：IODemo.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-01-02 17:00
 * 描    述：
 *****************************************************************/

import java.io.*;
import java.util.*;

public class IODemo {

	public static void test1() throws IOException {

		BufferedReader br = new BufferedReader(new InputStreamReader(System.in));
		int c;
		try {
			do {
				c = br.read();
				if (c != -1) {
					System.out.printf("%c", c);
				}	
			} while (c != 'q' && c != -1);
		} catch (IOException e) {
			e.printStackTrace();
		} finally {
			br.close();
		}
	}

	public static void test2() throws IOException {

		BufferedReader br = new BufferedReader(new InputStreamReader(System.in));
		String line;

		try {
			do {
				line = br.readLine();
				if (null != line) {
					System.out.println(line);
				}
			} while (null != line && !line.equals("end"));
		} catch (IOException e) {
		} finally {
			br.close();
		}
	}

	public static void test3() {

		InputStream is = null;

		try {

			is = new FileInputStream("/tmp/txt");
		} catch (FileNotFoundException e) {
			e.printStackTrace();
		}

		try {

			int c;
			while ((c = is.read()) != -1) {
				System.out.printf("%c", c);
			}
		} catch (IOException e) {

			e.printStackTrace();
		} finally {

			try {

				is.close();
			} catch (IOException e) {

				e.printStackTrace();
			}
		}
	}

	public static void test4() {

		InputStream is = null;
		OutputStream os = null;

		try {

			File file = new File("/tmp/txt");
			is = new FileInputStream(file);

			File outFile = new File("/tmp/txt.cpy");
			if (!outFile.exists()) {
				System.out.println("outFile not exists.");
			}
			os = new FileOutputStream(outFile);
			if (outFile.exists()) {
				System.out.println("outFile exists.");
			}
		} catch (FileNotFoundException e) {
			e.printStackTrace();
		}

		try {

			byte[] byteArr = new byte[is.available()];
			is.read(byteArr);
			for (byte b : byteArr) {
				System.out.printf("%c", b);
			}
			os.write(byteArr);
		} catch (IOException e) {

			e.printStackTrace();
		} finally {

			try {

				is.close();
				os.close();
				System.out.println("closed file.");
			} catch (IOException e) {

				e.printStackTrace();
			}
		}
	}

	public static void test5() throws IOException {

		File file = new File("/tmp/a.txt");
		OutputStream os = new FileOutputStream(file);
		OutputStreamWriter osw = new OutputStreamWriter(os, "GBK");
		osw.append("中文输入法");
		osw.append("\r\n");
		osw.append("english");
		osw.close();
		os.close();

		InputStream is = new FileInputStream(file);
		InputStreamReader isr = new InputStreamReader(is, "GBK");
		StringBuffer sb = new StringBuffer();
		while (isr.ready()) {
			sb.append((char)isr.read());
		}
		System.out.println(sb.toString());
		isr.close();
		is.close();
	}

	public static void test6() throws IOException {

		ByteArrayOutputStream bOutput = new ByteArrayOutputStream(12);

		while (bOutput.size() != 10) {
			bOutput.write((char)System.in.read());
		}

		byte[] b = bOutput.toByteArray();
		System.out.println("Print the content");
		for (int i = 0; i < b.length; i++) {
			System.out.print((char)b[i] + " ");
		}
		System.out.println("	");

		int c;
		ByteArrayInputStream bInput = new ByteArrayInputStream(b);
		System.out.println("Converting characters to upper case");
		while ((c = bInput.read()) != -1) {
			System.out.println(Character.toUpperCase((char)c));
		}
		bInput.reset();
	}

	public static void test7() throws IOException {

		DataInputStream dis = new DataInputStream(new FileInputStream("/tmp/txt"));
		DataOutputStream dos = new DataOutputStream(new FileOutputStream("/tmp/txt.cpy"));

		byte[] line = new byte[100];
		int size;
		while ((size = dis.read(line)) != -1) {
			byte[] upperLine = new byte[size];
			for (int i = 0; i < size; i++) {
				upperLine[i] = (byte)Character.toUpperCase(line[i]);
				System.out.printf("%c", upperLine[i]);
			}
			dos.write(upperLine);
		}
		dis.close();
		dos.close();
	}

	public static void test8() throws IOException {

		DataInputStream dis = new DataInputStream(new FileInputStream("/tmp/txt"));

		byte b;
		while ((b = dis.readByte()) != -1) {
			System.out.printf("%c", b);
		}
		dis.close();
	}

	public static void test9() throws IOException {

		File file = new File("/tmp/test_file.txt");
		file.createNewFile();

		FileWriter writer = new FileWriter(file, true);
		writer.write("菜鸟教程\n");
		char[] ch = new char[]{'菜', '鸟', '\n'};
		writer.write(ch);
		writer.write("菜鸟教程\n", 1, 4);
		writer.write(ch, 1, 2);
		writer.write('菜');
		writer.write('\n');
		writer.close();

		FileReader reader = new FileReader(file);
		System.out.println("\n\nread_char:A");
		ch = new char[(int)file.length()];
		int count = reader.read(ch);
		for (int i = 0; i < count; i++) {
			System.out.print(ch[i]);
		}
		reader.close();
		
		reader = new FileReader(file);
		System.out.println("\n\nread__");
		while ((count = reader.read()) != -1) {
			System.out.printf("%c", count);
		}
		reader.close();

		reader = new FileReader(file);
		System.out.println("\n\nread_char:A_int_int");
		count = reader.read(ch, 0, ch.length);
		for (int i = 0; i < count; i++) {
			System.out.print(ch[i]);
		}
		System.out.println("file length: " + file.length());
		reader.close();
	}

	public static void test10() {

		Scanner scan = new Scanner(System.in);

		while (scan.hasNextInt()) {
			int in = scan.nextInt();
			System.out.println(in);
		}

		while (scan.hasNextLine()) {
			String in = scan.nextLine();
			System.out.println(in);
		}
	}

	public static void main(String[] args) throws IOException {

		test10();
	}
}
