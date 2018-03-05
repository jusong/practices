/*****************************************************************
 * 文件名称：SendMail.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2017-12-26 16:48
 * 描    述：
 *****************************************************************/

import java.util.*;
import java.io.*;
import javax.mail.*;
import javax.mail.internet.*;
import javax.activation.*;

public class SendMail {

    /**
     * 给单个邮箱发送邮件
     * @param form 发送者
     * @param to 接收者
     * @param content 发送内容
     * @param host 发送邮件主机
     * @return boolean
     */
    public boolean sendMail(String from, String to, String content, String host) {

        if (from.length() <= 0 || to.length() <= 0 || content.length() <= 0) {
            return false;
        }

        try {

            Properties properties = System.getProperties();
            properties.setProperty("mail.smtp.host", host);
            Session session = Session.getDefaultInstance(properties);

            MimeMessage msg = new MimeMessage(session);
            msg.setFrom(new InternetAddress(from));
            msg.addRecipient(Message.RecipientType.TO, new InternetAddress(to));
            msg.setSubject("This is Subject.");
            msg.setText(content);
            Transport.send(msg);
        } catch (MessagingException e) {

            e.printStackTrace();
        }

        return true;
    }

    /**
     * 给多个邮箱发送邮件
     * @param form 发送者
     * @param to 接收者数组
     * @param content 发送内容
     * @param host 发送邮件主机
     * @return boolean
     */
    public boolean sendMail(String from, String[] to, String content, String host) {

        if (from.length() <= 0 || to.length <= 0 || content.length() <= 0) {
            return false;
        }

        try {

            Properties properties = System.getProperties();
            properties.setProperty("mail.smtp.host", host);
            Session session = Session.getDefaultInstance(properties);

            MimeMessage msg = new MimeMessage(session);
            msg.setFrom(new InternetAddress(from));
            InternetAddress[] addrList = new InternetAddress[to.length];
            for (int i = 0; i < to.length; i++) {
                addrList[i] = new InternetAddress(to[i]);
            }
            msg.addRecipients(Message.RecipientType.TO, addrList);
            msg.setSubject("This is Subject.");
            msg.setText(content);
            Transport.send(msg);
        } catch (MessagingException e) {

            e.printStackTrace();
        }

        return true;
    }

    /**
     * 给单个邮箱发送HTML邮件
     * @param form 发送者
     * @param to 接收者
     * @param content 发送内容
     * @param host 发送邮件主机
     * @return boolean
     */
    public boolean sendHtmlMail(String from, String to, String content, String host) {

        if (from.length() <= 0 || to.length() <= 0 || content.length() <= 0) {
            return false;
        }

        try {

            Properties properties = System.getProperties();
            properties.setProperty("mail.smtp.host", host);
            Session session = Session.getDefaultInstance(properties);

            MimeMessage msg = new MimeMessage(session);
            msg.setFrom(new InternetAddress(from));
            msg.addRecipient(Message.RecipientType.TO, new InternetAddress(to));
            msg.setSubject("This is Subject.");
            msg.setContent(content, "text/html; charset=utf-8");
            Transport.send(msg);
        } catch (MessagingException e) {

            e.printStackTrace();
        }

        return true;
    }

    /**
     * 给多个邮箱发送HTML邮件
     * @param form 发送者
     * @param to 接收者数组
     * @param content 发送内容
     * @param host 发送邮件主机
     * @return boolean
     */
    public boolean sendHtmlMail(String from, String[] to, String content, String host) {

        if (from.length() <= 0 || to.length <= 0 || content.length() <= 0) {
            return false;
        }

        try {

            Properties properties = System.getProperties();
            properties.setProperty("mail.smtp.host", host);
            Session session = Session.getDefaultInstance(properties);

            MimeMessage msg = new MimeMessage(session);
            msg.setFrom(new InternetAddress(from));
            InternetAddress[] addrList = new InternetAddress[to.length];
            for (int i = 0; i < to.length; i++) {
                addrList[i] = new InternetAddress(to[i]);
            }
            msg.addRecipients(Message.RecipientType.TO, addrList);
            msg.setSubject("This is Subject.");
            msg.setContent(content, "text/html; charset=utf-8");
            Transport.send(msg);
        } catch (MessagingException e) {

            e.printStackTrace();
        }

        return true;
    }

    /**
     * 给多个邮箱发送带附件邮件
     * @param form 发送者
     * @param to 接收者数组
     * @param content 发送内容
     * @param filename 附件名称
     * @param host 发送邮件主机
     * @return boolean
     */
    public boolean sendActMail(String from, String[] to, String content, String filename, String host) {

        if (from.length() <= 0 || to.length <= 0 || content.length() <= 0 || filename.length() <= 0) {
            return false;
        }

        try {

            Properties properties = System.getProperties();
            properties.setProperty("mail.smtp.host", "smtp.exmail.qq.com");
            properties.put("mail.smtp.auth", "true");
            //properties.setProperty("mail.user", "jiafd@edu-china.com");
            //properties.setProperty("mail.password", "Jijingqian1992");
            Session session = Session.getDefaultInstance(properties, new Authenticator() {
                public PasswordAuthentication getPasswordAuthentication() {
                    return new PasswordAuthentication("jiafd@edu-china.com", "Jijingqian1992");
                }
            });

            MimeMessage msg = new MimeMessage(session);
            msg.setFrom(new InternetAddress(from));
            InternetAddress[] addrList = new InternetAddress[to.length];
            for (int i = 0; i < to.length; i++) {
                addrList[i] = new InternetAddress(to[i]);
            }
            msg.addRecipients(Message.RecipientType.TO, addrList);
            msg.setSubject("This is Subject.");

            Multipart multipart = new MimeMultipart();

            BodyPart msgBodyPart = new MimeBodyPart();
            msgBodyPart.setText(content);
            multipart.addBodyPart(msgBodyPart);

            msgBodyPart = new MimeBodyPart();
            DataSource source = new FileDataSource(filename);
            msgBodyPart.setDataHandler(new DataHandler(source));
            msgBodyPart.setFileName(filename);
            multipart.addBodyPart(msgBodyPart);

            msg.setContent(multipart);
            Transport.send(msg);
        } catch (MessagingException e) {

            e.printStackTrace();
        }

        return true;
    }

    public static void main(String[] args) {

        String from = "jiafd@edu-china.com";

        String to = "blacknc@163.com";

        String host = "localhost";

        SendMail sm = new SendMail();

        //sm.sendMail(from, to, "This is Content.", host);

        //        String[] tos = new String[2];
        //        tos[0] = to;
        //        tos[1] = "jusonlinux@163.com";
        //        sm.sendMail(from, tos, "This is Content.", host);
        //

        //        FileInputStream file = null;
        //        try {
        //
        //            file = new FileInputStream("/tmp/runoob.html");
        //            byte[] fileBytes = new byte[file.available()];
        //            file.read(fileBytes);
        //            String content = new String(fileBytes); 
        //            String[] tos = new String[2];
        //            tos[0] = to;
        //            tos[1] = "jusonlinux@163.com";
        //            sm.sendHtmlMail(from, tos, content, host);
        //        } catch (IOException e) {
        //
        //            System.out.println("Exception");
        //        } finally {
        //
        //            try {
        //
        //                if (null != file) {
        //
        //                    file.close();
        //                }
        //            } catch (IOException e) {
        //
        //                    e.printStackTrace();
        //                }
        //            }

        String[] tos = new String[2];
        tos[0] = to;
        tos[1] = "jusonlinux@163.com";
        sm.sendActMail(from, tos, "带有附件的邮件", "/tmp/runoob.html", host);
    }
}
