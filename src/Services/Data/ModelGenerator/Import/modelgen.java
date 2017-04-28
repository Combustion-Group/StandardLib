import java.io.File;
import java.io.FileNotFoundException;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;

/**
 * Created by Zach on 3/20/17.
 */
public class modelgen {

    private static void showHelp() {
        System.out.println("Arguments: <file_name> <namespace> <class> <table> <author> <email> <var1type> <var1name> ... <var7type> <var7name>");
    }

    public static void main(String[] args) throws FileNotFoundException {

        String file_name namespace, className, table, userName, email;
        List<Variable> variables = new ArrayList<>();

        if (args.length > 0) {
            if (args[0].equalsIgnoreCase("help")) {
                showHelp();
                return;
            } else {
                file_name = args[0]
                namespace = args[1];
                className = args[2];
                table = args[3];
                userName = args[4];
                email = args[5];
                for (int i = 6; i < args.length - 1; i += 2) {
                    variables.add(new Variable(args[i], args[i + 1]));
                }
            }
        } else {
            showHelp();
            return;
//            Scanner in = new Scanner(System.in);
//
//            System.out.print("Enter namespace: ");
//            namespace = in.nextLine();
//
//            System.out.print("Enter class name: ");
//            className = in.nextLine();
//
//            System.out.print("Enter table: ");
//            table = in.nextLine();
//
//            System.out.print("Enter user name: ");
//            userName = in.nextLine();
//
//            System.out.print("Enter email: ");
//            email = in.nextLine();
//
//            System.out.println("Enter variables (e.g. int id). When done, enter '*Q'");
//            String input = "";
//            while (!input.equals("*Q")) {
//                input = in.nextLine();
//                if (input.equals("*Q")) {
//                    break;
//                }
//                String[] parts = input.split(" ");
//                variables.add(new Variable(parts[0], parts[1]));
//            }
        }

//        File dir = new File(namespace);
//        dir.mkdirs();
        File file = new File(file_name + ".php");
        System.out.println();
        PrintWriter writer = new PrintWriter(file);

        writer.println("<?php");
        writer.println();
        writer.println("namespace " + namespace + ";");
        writer.println();
        writer.println("use Combustion\\StandardLib\\Models\\Model;");
        writer.println();
        writer.println("/**");
        writer.println(" * Class " + className);
        writer.println(" * @package " + namespace);
        writer.println(" * @author " + userName + " <" + email + ">");
        writer.println(" */");
        writer.println("class " + className + " extends Model");
        writer.println("{");
        writer.println();
        writer.println("    /**");
        writer.println("     * @var string");
        writer.println("     */");
        writer.println("    protected $table = \'" + table + "\';");
        writer.println();

        writer.println("    // columns");
        for (Variable variable : variables) {
            writer.println("    const " + variable.getLowerSnakeCase().toUpperCase() + " = \'" + variable.getLowerSnakeCase() + "\';");
        }

        writer.println();

        writer.println("    /**");
        writer.println("     * @var array");
        writer.println("     */");
        writer.println("    protected $fillable = [");
        for (int i = 0; i < variables.size(); i++) {
            Variable variable = variables.get(i);
            if (variable.getLowerSnakeCase().equals("id")) {
                continue;
            }
            String line = "        self::" + variable.getLowerSnakeCase().toUpperCase();
            if (i < variables.size() - 1) {
                line += ",";
            }
            writer.println(line);
        }
        writer.println("    ];");
        writer.println();

        writer.println("    /**");
        writer.println("     * @var array");
        writer.println("     */");
        writer.println("    protected $visible = [");
        for (int i = 0; i < variables.size(); i++) {
            Variable variable = variables.get(i);
            String line = "        self::" + variable.getLowerSnakeCase().toUpperCase();
            if (i < variables.size() - 1) {
                line += ",";
            }
            writer.println(line);
        }
        writer.println("    ];");

        for (Variable variable : variables) {
            writer.println();
            writer.println("    /**");
            writer.println("     * @return " + variable.getType());
            writer.println("     */");
            writer.println("    public function get" + variable.getUpperCamelCase() + "() : " + variable.getType());
            writer.println("    {");
            writer.println("        return (" + variable.getType() + ")$this->getAttribute(self::" + variable.getUpperSnakeCase() + ");");
            writer.println("    }");
            writer.println();
            writer.println("    /**");
            writer.println("     * @param " + variable.getType() + " $" + variable.getLowerCamelCase());
            writer.println("     * @return " + className);
            writer.println("     */");
            writer.println("    public function set" + variable.getUpperCamelCase() + "(" + variable.getType() + " $" + variable.getLowerCamelCase() + ") : self");
            writer.println("    {");
            writer.println("        $this->setAttribute(self::" + variable.getUpperSnakeCase() + ", $" + variable.getLowerCamelCase() + ");");
            writer.println("        return $this;");
            writer.println("    }");
        }

        writer.println("}");
        writer.close();

    }

    private static class Variable {

        private String type, name;

        Variable(String type, String name) {
            this.type = type;
            this.name = name;
        }

        String getType() {
            return type;
        }

        String getLowerSnakeCase() {
            return name;
        }

        String getUpperSnakeCase() {
            return name.toUpperCase();
        }

        String getUpperCamelCase() {
            String[] parts = name.split("_");

            String result = "";

            for (String part : parts) {
                result += (part.charAt(0) + "").toUpperCase();
                result += part.substring(1).toLowerCase();
            }

            return result;
        }

        String getLowerCamelCase() {
            String upperCamelCase = getUpperCamelCase();
            return upperCamelCase.substring(0, 1).toLowerCase() + upperCamelCase.substring(1);
        }
    }
}
