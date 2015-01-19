## "Tabs and Indents" tab

- [x] Use tab character - "Generic.WhiteSpace.DisallowSpaceIndent.SpacesUsed"
- [ ] Keep indents on empty lines - "Squiz.WhiteSpace.SuperfluousWhitespace.EndLine"


## "Spaces" tab

### "Before Parentheses" sub-section

- [ ] Function declaration parentheses - "Squiz.Functions.FunctionDeclaration" (TODO: no fixing)
- [ ] Function call parentheses - "CodingStandard.Methods.FunctionCallSignature.SpaceBeforeOpenBracket"
- [x] 'if' parentheses - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing)
- [x] 'for' parentheses - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing)
- [x] 'while' parentheses - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing)
- [x] 'switch' parentheses - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing)
- [x] 'catch' parentheses - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing)
- [ ] Array initializer parentheses - "CodingStandard.Array.Array.SpaceAfterKeyword"

### "Around Operators" sub-section

- [x] Assignment operators (=, +=, ...) - "Squiz.WhiteSpace.OperatorSpacing.NoSpaceBefore/NoSpaceAfter"
- [x] Logical operators (&&, ||) - "Squiz.WhiteSpace.LogicalOperatorSpacing.NoSpaceBefore/NoSpaceAfter"
- [x] Equality operators (==, !=) - "Squiz.WhiteSpace.OperatorSpacing.NoSpaceBefore/NoSpaceAfter"
- [x] Relational operators (<, >, <=, >=) - "Squiz.WhiteSpace.OperatorSpacing.NoSpaceBefore/NoSpaceAfter"
- [x] Bitwise operators (&, |, ^) - "(Squiz.WhiteSpace.OperatorSpacing.NoSpaceBeforeAmp/NoSpaceAfterAmp", "Squiz.WhiteSpace.OperatorSpacing.NoSpaceBefore/NoSpaceAfter"
- [x] Additive operators (+, -) - "Squiz.WhiteSpace.OperatorSpacing.NoSpaceBefore/NoSpaceAfter"
- [x] Multiplicative operators (*, /, %) - "Squiz.WhiteSpace.OperatorSpacing.NoSpaceBefore/NoSpaceAfter"
- [x] Shift operators (<<, >>, >>>) - (TODO: Not covered; Priority: Low)
- [ ] Unary additive operators (+, -, ++, --) - "CodingStandard.Formatting.SpaceUnaryOperator"
- [x] Concatenation (.) - "CodingStandard.Strings.ConcatenationSpacing"

### "Before Left Brace" sub-section

- [x] Class left brace - "CodingStandard.Classes.ClassDeclaration.OpenBraceNewLine"
- [x] Function left brace - "CodingStandard.Functions.FunctionDeclaration.BraceOnSameLine"
- [x] 'if' left brace - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing)
- [x] 'else' left brace - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing)
- [x] 'while' left brace - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing)
- [x] 'do' left brace - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing)
- [x] 'switch' left brace - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing)
- [x] 'try' left brace - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing)
- [x] 'catch' left brace - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing)
- [x] 'finally' left brace - (TODO: Not covered; Priority: Low)

### "Before keywords" sub-section

- [x] 'else' keyword - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing), "CodingStandard.WhiteSpace.ControlStructureSpacing.LineBeforeOpen"
- [x] 'while' keyword - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing)
- [x] 'catch' keyword - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing), "CodingStandard.WhiteSpace.ControlStructureSpacing.ContentBeforeStart"
- [x] 'finally' keyword - (TODO: Not covered; Priority: Low)

### "Within" sub-section

- [ ] Brackets - "Squiz.Arrays.ArrayBracketSpacing.SpaceAfterBracket/SpaceBeforeBracket"
- [ ] Array initializer parentheses - "CodingStandard.Array.Array.SpaceAfterOpen/SpaceBeforeClose"
- [ ] Grouping parentheses - (TODO: Not covered; Priority: Low)
- [ ] Function call parentheses - "CodingStandard.Methods.FunctionCallSignature.SpaceAfterOpenBracket/SpaceBeforeCloseBracket"
- [ ] Function declaration parentheses - "Squiz.Functions.FunctionDeclarationArgumentSpacing.SpacingAfterOpen/SpacingBeforeClose"
- [x] 'if' parentheses - "CodingStandard.WhiteSpace.ControlStructureSpacing.SpacingAfterOpenBrace/SpaceBeforeCloseBrace"
- [x] 'for' parentheses - "CodingStandard.WhiteSpace.ControlStructureSpacing.SpacingAfterOpenBrace/SpaceBeforeCloseBrace", "Squiz.ControlStructures.ForLoopDeclaration.SpacingAfterOpen/SpacingBeforeClose"
- [x] 'while' parentheses - "CodingStandard.WhiteSpace.ControlStructureSpacing.SpacingAfterOpenBrace/SpaceBeforeCloseBrace"
- [x] 'switch' parentheses - "CodingStandard.WhiteSpace.ControlStructureSpacing.SpacingAfterOpenBrace/SpaceBeforeCloseBrace"
- [x] <?= and ?> - (TODO: Not covered; Priority: Low)

### "In Ternary Operator (?:)" sub-section

- [x] Before '?' - "Squiz.WhiteSpace.OperatorSpacing.NoSpaceBefore"
- [x] After '?' - "Squiz.WhiteSpace.OperatorSpacing.NoSpaceAfter"
- [x] Before ':' - "Squiz.WhiteSpace.OperatorSpacing.NoSpaceBefore"
- [x] After ':' - "Squiz.WhiteSpace.OperatorSpacing.NoSpaceAfter"
- [ ] Between '?' and ':' - (TODO: Not covered; Priority: Normal; #53)

### "Other" sub-section

- [ ] Before comma - (TODO: Not covered; Priority: Normal; #54)
- [x] After comma - (TODO: Not covered; Priority: Normal; #54)
- [ ] Before semicolon - "Squiz.WhiteSpace.SemicolonSpacing.Incorrect"
- [x] After semicolon - "Squiz.ControlStructures.ForLoopDeclaration.NoSpaceAfterFirst/NoSpaceAfterSecond"
- [ ] After type cast - "Generic.Formatting.NoSpaceAfterCast.SpaceFound"
- [ ] Before unary Not (!) - (TODO: Not covered; Priority: Normal; #55)
- [ ] After unary Not (!) - "CodingStandard.Formatting.NoSpaceAfterBooleanNot.SpaceFound"


## "Wrapping and Braces"

### Right margin (columns): Default (General)

### "Keep when reformatting" sub-section

- [x] Line breaks
- [ ] Comment at first column - "CodingStandard.Commenting.InlineComment.SpacingBefore"
- [x] Control statement in one line
- [ ] Simple methods in one line - "CodingStandard.Functions.FunctionDeclaration.ContentAfterBrace"

### "Braces placement" sub-section

- In class declaration: Next line - "CodingStandard.Classes.ClassDeclaration.OpenBraceNewLine"
- In function declaration: Next line - "CodingStandard.Functions.FunctionDeclaration.BraceOnSameLine"
- Other: End of line - "CodingStandard.ControlStructures.ControlSignature" (TODO: no fixing)

### "Extends/implements list": "Do not wrap" - (TODO: Not covered; Priority: Low)

- [ ] Align when multiline - (TODO: Not covered; Priority: Low)

### "Extends/implements keyword": "Do not wrap" (consider "Wrap Always") - (TODO: Not covered; Priority: Low)

### "Function declaration parameters": "Do not wrap" - (TODO: Not covered; Priority: Low)

- [ ] Align when multiline - "CodingStandard.Functions.FunctionDeclaration.Indent"
- [ ] New line after '('
- [ ] Place ')' on new line
- [x] Keep ')' and '{' on one line - "CodingStandard.Functions.FunctionDeclaration.NewlineBeforeOpenBrace"

### "Function call arguments": "Do not wrap"

- [ ] Align when multiline
- [ ] New line after '('
- [ ] Place ')' on new line

### "Chained method calls": "Do not wrap" (consider "Wrap Always")

- [ ] Align when multiline
- Place ';' on new line

### "Class field/constant groups" sub-section

- [ ] Align fields in columns
- [ ] Align constants

### "'if()' statement" sub-section

- Force braces: Always
- [x] 'else' on new line
- [ ] Special 'else if' treatment

### "for()/foreach() statements": "Do not wrap"

- [ ] Align when multiline
- [ ] New line after '('
- [ ] Place ')' on new line
- Force braces: Always

### "'while()' statement" sub-section

- Force braces: Always

### "'do ... while()' statement" sub-section

- Force braces: Always
- [ ] 'while' on new line

### "'switch' statement" sub-section

- [ ] Indent 'case' branches

### "'try' statement" sub-section

- [x] 'catch' on new line
- [ ] 'finally' on new line

### "Binary expressions": "Do not wrap" (consider "Wrap if long")

- [ ] Align when multiline
- [ ] Operation sign on next line
- [ ] New line after '('
- [ ] Place ')' on new line

### "Assignment statement": "Do not wrap"

- [ ] Assignment sign on new line
- [ ] Align consecutive assignments

### "Ternary operation": "Do not wrap"

- [ ] Align when multiline
- [ ] '?' and ':' signs on next line

### "Array initializer": "Do not wrap"

- [ ] Align when multiline
- [ ] New line after '('
- [ ] Place ')' on new line

### "Modifier list" sub-section

- [ ] Wrap after modifier list


## "Blank Lines"

### "Keep Maximum Blank Lines" sub-section

- In declarations: 1
- In code: 2
- Before '}': 1

### "Minimum Blank Lines" sub-section

- After namespace: 2
- Before 'Use' statements: 1
- After 'Use' statements: 1
- Around class: 2
- After class header: 1
- Around field: 1
- Around method: 1
- Before method body: 0
- Around class constants: 0


## "PHPDoc"

- [x] Align parameter/property names
- [ ] Keep blank lines
- [x] Blank lines around parameters
- [x] Blank line before first tag
- [x] Align tag comments
- [ ] Wrap long lines (consider turning on)


## "Other"

- [ ] Indent code in PHP tags
- [x] Convert True/False constants to: Lower case
- [x] Convert Null constant to: Lower case
- [x] Blank line before return statement
- [ ] Spaces around variable/expression in brackets

### "Code Commenting"

- [ ] Line comment at first column

### "Array declaration style"

- [ ] Force short declaration style
- [ ] Align key-value pairs









