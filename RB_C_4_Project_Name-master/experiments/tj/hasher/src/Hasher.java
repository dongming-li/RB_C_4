/**
 * Created by Thomas John Wesolowski on 9/9/2017.
 * Contact email: wojoinc@iastate.edu
 */


import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.PrintStream;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

import gnu.getopt.Getopt;

public class Hasher {
    public int getBuffer_block_size() {
        return buffer_block_size;
    }

    public void setBuffer_block_size(int buffer_block_size) {
        this.buffer_block_size = buffer_block_size;
    }

    private int buffer_block_size;

    public static void printUsage() {
        System.out.println("hasher -i <inputfile> [-b <blocksize>] [-o <outfile>]");
    }
    private String calcFileHash(String filepath) {
        byte hash[] = null;
        try {

            FileInputStream fistream = new FileInputStream(filepath);
            FileChannel fc = fistream.getChannel();
            MessageDigest md = MessageDigest.getInstance("SHA-256");
            ByteBuffer bb = ByteBuffer.allocateDirect(buffer_block_size);
            int bytes_read = 1;
            /*
             * Reads bytes from the file channel, and updates the MessageDigest's internals
             * with the value of the byte buffer.
             */
            while (bytes_read > 0) {
                bytes_read = fc.read(bb);
                if (bytes_read > 0) {
                    //TODO find a better way to check for endianness
                    //Flips buffer to correct for endianness
                    bb.flip();
                    md.update(bb);
                }
            }
            // digest internals, and compute final hash
            hash = md.digest();

            //cleanup and close filestream, filechannel is closed upon closing the stream
            fistream.close();


        } catch (NoSuchAlgorithmException e) {
            e.printStackTrace();
        }
        //TODO improve exception handling. These are placeholders
        catch (FileNotFoundException ex) {
            ex.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }

        //convert hash into human readable hexadecimal
        StringBuffer hexbuf = new StringBuffer();
        for (int i = 0; i < hash.length; i++) {
            hexbuf.append(String.format("%02x", hash[i]));
        }

        return hexbuf.toString();
    }

    public Hasher() {
        buffer_block_size = 1024;
    }

    public Hasher(int buffer_block_size) {
        this.buffer_block_size = buffer_block_size;
    }

    public static void main(String[] args) {
        if (args.length == 0) {
            printUsage();
            System.exit(-1);
        }

        Getopt gpt = new Getopt("hasher", args, ":i:b:o:h");
        String inputFile = null;
        String outputFile = null;
        Hasher hasher = new Hasher(65537);
        int c;
        String arg;
        while ((c = gpt.getopt()) != -1) {
            switch (c) {
                case 'h':
                    printUsage();
                    break;
                case 'i':
                    arg = gpt.getOptarg();
                    if (arg != null) inputFile = arg;
                    else {
                        printUsage();
                        System.exit(-1);
                    }
                    break;
                //
                case 'b':
                    arg = gpt.getOptarg();
                    if (arg != null) hasher.setBuffer_block_size(Integer.parseInt(arg));
                    break;
                case 'o':
                    arg = gpt.getOptarg();
                    if (arg != null) outputFile = arg;
                    break;
                //
                case '?':
                    break; // getopt() already printed an error
                //
                default:
                    System.out.print("getopt() returned " + c + "\n");
            }
        }
        if (outputFile == null) {
            System.out.println(hasher.calcFileHash(inputFile));
        }
    }
}
