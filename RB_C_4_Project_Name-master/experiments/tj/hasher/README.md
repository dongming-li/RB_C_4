<b>Experiment driectory for hasher.</b>

Currently, the Hasher will compute a SHA-256 hash checksum of the file located at <i>testfiles/single_files/test.txt</i> if run through a debugger. However, the <b>out</b> directory now includes a precompiled jarfile with the necessary dependencies prelinked.

The command-line interface makes use of the <a href="https://www.gnu.org/software/gnuprologjava/api/gnu/getopt/package-summary.html">Java port</a> of the GNU GetOpt C library for parsing commandline arguments, as this provides far more functionality then the builtin java alternatives, and is the easiest to use.

Usage:
<p>
"java -jar hasher.jar -i &lt;inputfile&gt; [-b &lt;blocksize&gt;] [-o &lt;outfile&gt;]"


<p>SHA-256 was chosen as the <a href="https://docs.oracle.com/javase/7/docs/api/java/security/MessageDigest.html">MessageDigest</a> documentation states that:</p>
<blockquote cite="https://docs.oracle.com/javase/7/docs/api/java/security/MessageDigest.html">  
Every implementation of the Java platform is required to support the following standard MessageDigest algorithms:

<ul>
<li>MD5</li>
<li>SHA-1</li>
<li>SHA-256</li>
</ul>
These algorithms are described in the MessageDigest section of the Java Cryptography Architecture Standard Algorithm Name Documentation. Consult the release documentation for your implementation to see if any other algorithms are supported.
</blockquote>
</p>

<table style="width:100%">
    <tr>Current tweaks implemented:</tr>
    <tr>
        <th>Feature</th>
        <th>Description</th>
    </tr>
    <tr>
        <td>Specify buffer block size</td>
        <td>Allows for changing buffer block size, to make calculating files of varying sizes quicker.</td>
        
</table>

<table style="width:100%">
    <tr>Planned experiments and features:</tr>
    <tr>
        <th>Feature</th>
        <th>Description</th>
    </tr>
    <tr>
        <td>Add ability to specify different algorithms</td>
        <td>Allows for specification of which hashing algorithm to use. Particularly useful as Java has a method for determining whether or not a particular algorithm is available. Documentation for Mesage Digest suggests not all implementations of the JVM support all algorithms.</td>
        
</table>
